<?php

namespace App\Form;

use App\Entity\Salons;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class SalonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => "Titre"
            ])
            ->add('description', TextType::class, [
                'label' => "Description"
            ])
            ->add('user', EntityType::class, [ // Use EntityType for user association
                'class' => Users::class,
                'choice_label' => 'username',
                'label' => 'Utilisateur'
            ])
            ->add('privacy')
            ->add('createdAt', DateTimeType::class)
            ->add('save', SubmitType::class,
                ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salons::class,
        ]);
    }
}
