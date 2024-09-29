<?php

namespace App\Form;

use App\Entity\Proposal;
use App\Entity\Sujet;
use App\Entity\Vote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', HiddenType::class, [
                'property_path' => 'user.email',
                'disabled' => true
            ])
            ->add('notes', ChoiceType::class, [
                'choices' => [

                    'Inadapté' => 'inadapte',
                    'Passable' => 'passable',
                    'Bien' => 'bien',
                    'Très Bien' => 'tresbien',
                    'Excellent' => 'excellent',

                ],
                'empty_data' => 'bien'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vote::class
        ]);
    }
}
