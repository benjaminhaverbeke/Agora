<?php

namespace App\Form;

use App\Entity\Proposal;
use App\Entity\Sujet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {




        if($options['vote'] === true) {





            $builder
                ->add('proposals', CollectionType::class, [
                    'entry_type' => ProposalType::class,
                    'entry_options' => [

                        'vote' => true,
                        'mapped' =>true
                    ],
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Voter',
                    'attr' => ['class' => 'btn'],
                ]);


        }
        else {
            $builder
                ->add('title', TextType::class, [

                        'label' => 'Titre',
                        'attr' => [
                            'class' => 'sujet-input-title',
                            'placeholder' => 'Titre',
                        ]
                    ]
                )
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'attr' => [
                        'placeholder' => 'Description',
                    ]
                ])

                ->add('save', SubmitType::class, [
                    'label' => 'Enregistrer',
                    'attr' => ["class" => "btn"]
                ]);
        }


        }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
            'vote' => false,

        ]);
    }
}
