<?php

namespace App\Form;

use App\Entity\Sujets;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ]
            )
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('campagne_date', DateTimeType::class, [
                'label' => "Fin de la campagne",
                'widget' => 'choice',
            ])
            ->add('vote_date', DateTimeType::class, [
                'label' => "Cloture des votes",
                'widget' => 'choice',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujets::class,
        ]);
    }
}