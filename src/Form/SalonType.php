<?php

namespace App\Form;

use App\Entity\Salons;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => "10",
                    'placeholder' => 'Description',
                ]
            ])
            ->add('dateCampagne', DateTimeType::class, [

                'label' => "Fin de la campagne",
                'attr' => [
                    'placeholder' => 'Fin de la campagne',
                ]
            ])
            ->add('dateVote', DateTimeType::class, [
                'label' => "Cloture des votes",
                'attr' => [
                    'placeholder' => 'Cloture des votes',
                ]

            ])
            ->add('save', SubmitType::class ,[
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn'],
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salons::class,
        ]);

    }
}
