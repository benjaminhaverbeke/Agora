<?php

namespace App\Form;

use App\Entity\Proposal;
use App\Entity\Salon;
use App\Entity\Sujet;
use App\Entity\User;
use App\Entity\Vote;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class ProposalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $isVote = $options['vote'] ?? 'vote';

        if ($isVote) {

            $builder
                ->add('title', TextType::class, [
                    'label' => 'Titre',
                    'attr' => [
                        'class' => 'vote-title'
                    ],
                    'disabled' => true

                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'attr' => [
                        'class' => 'vote-description'
                    ],
                    'disabled' => true
                ])
                ->add('vote', VoteType::class,

                    [
                        'mapped' => false,
                        'data' => new Vote


                    ]);

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
                        'rows' => "15",
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
            'vote' => false,

        ]);
    }
}
