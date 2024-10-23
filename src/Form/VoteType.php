<?php

namespace App\Form;

use App\Entity\Proposal;
use App\Entity\Sujet;
use App\Entity\User;
use App\Entity\Vote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
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
use Symfony\Component\Security\Http\Attribute\CurrentUser;


class VoteType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('user', HiddenType::class, [
//                'property_path' => 'user.email',
//                'disabled' => true,
//                'mapped' => false
//            ])
            ->add('notes', TextType::class,
                [
                'data' => 'bien',
                'attr' => [

                    'class' => 'original-select-vote',
                    'data-vote-target' => 'originalSelect'
                ],



            ])->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event): void {
                $vote = $event->getData();
                $proposal = $event->getForm()->getParent()->getData();

                $vote->setProposal($proposal);
                $vote->setSujet($proposal->getSujet());
                $vote->setUser($this->security->getUser());

                $proposal->addVote($vote);
            });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vote::class
        ]);
    }
}
