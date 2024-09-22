<?php

namespace App\Form;

use App\Entity\Proposal;
use App\Entity\Salon;
use App\Entity\Sujet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $isVote = $options['vote'] ?? 'vote';

        if($isVote) {

            $builder
                ->add('votes', CollectionType::class, [
                'entry_type' => VoteType::class,
                'allow_delete' => false,
                'allow_add' => true,
                'by_reference' => false,

            ]);

            $builder->get('votes')->addEventListener(FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($isVote) {

                            $form = $event->getForm();

                            $form->add('notes', ChoiceType::class, [
                                'attr' => ['class' => 'label-active'],
                                'expanded' => true,
                                'choices' => [
                                    'Inadapte' => 'inadapte',
                                    'Passable' => 'passable',
                                    'Bien' => 'bien',
                                    'Très Bien' => 'tresbien',
                                    'Excellent' => 'excellent',
                                ]

                            ]);
                });


        }
        else{

            $builder
                ->add('title', TextType::class, [
                    'attr' => [
                        'placeholder' => "Titre",
                        'class' => 'title-center',
                    ]
                ])
                ->add('description', TextareaType::class, [
                    'label' => false,
                    'attr' => [
                        'rows' => "10",
                        'placeholder' => 'Description',
                    ]
                ])
                ->add('save', SubmitType::class, [
                    "label" => "Enregistrer",
                    'attr' => [
                        'class' => 'btn',
                    ]
                ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proposal::class,
        ]);
    }
}
