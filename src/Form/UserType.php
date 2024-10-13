<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "label" => "Nom d'utilisateur",
                'attr' => ['placeholder' => "Nom d'utilisateur"],

            ])
            ->add('email', EmailType::class, [
                "label" => "Email",
                'attr' => ['placeholder' => "Email"],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'invalid_message'=> 'Les mots de passe ne correspondent pas',
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => 'Confirmation du mot de passe',
                    'attr' => ['placeholder' => 'Confirmation du mot de passe']],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                ],
                'constraints' => [

                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => "Votre mot de passe doit contenir au moins {{ limit }} caractères",
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/",
                        'match' => true,

                        'message' => 'Votre mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                "label" => "Enregistrer",
                "attr" => ["class" => "btn"]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
