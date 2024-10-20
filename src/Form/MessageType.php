<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Salon;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                "label" => "Message",
                "attr" => [
                    'placeholder' => 'Message',
                    'data-chat-target' => 'input',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => "send",

                'attr' => [
                    'class' => "material-symbols-outlined",
                    'data-action' => "click->chat#scrolldown",
                    'data-chat-target' => 'input',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }
}
