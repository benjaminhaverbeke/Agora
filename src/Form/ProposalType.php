<?php

namespace App\Form;

use App\Entity\Proposal;
use App\Entity\Salon;
use App\Entity\Sujet;
use App\Entity\User;
use App\Entity\Vote;
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

        if ($isVote) {

            $builder
                ->add('title', TextType::class, [
                    'label' => 'Titre',
                    'disabled' => true,
                ])
                ->add('votes', CollectionType::class, [
                        'entry_type' => VoteType::class,
                        'allow_add' => true,
                    ]

                );

            $builder->get('votes')->addEventListener(FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($isVote) {

                    $form = $event->getForm();


                    $form->add('notes', VoteType::class, [


                    ]);
                });

        } else {

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
            'vote' => false
        ]);
    }
}
