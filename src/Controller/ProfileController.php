<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\InvitationRepository;
use App\Repository\SalonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(
        SalonRepository      $sm,
        InvitationRepository $im,
        #[CurrentUser] User  $currentUser
    ): Response
    {

        $salons = $currentUser->getSalons();

        $invits = $im->findByReceiverField($currentUser);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'salons' => $salons,
            'invits' => $invits,
        ]);
    }

    #[Route('/profile/invit/{id}', name: 'profile.invit', requirements: ['id' => '\d+'])]
    public function invit(
        int                    $id,
        EntityManagerInterface $em,
        InvitationRepository   $im,
        #[CurrentUser] User    $currentUser
    ): Response
    {

        $invit = $im->find($id);
        $salon = $invit->getSalon();
        $salon->addUser($currentUser);
        $salon->removeInvitation($invit);

        $em->persist($salon);
        $em->flush();

        return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);

    }

    #[Route('/profile/invit/{id}/refuse', name: 'profile.refuse', requirements: ['id' => '\d+'])]
    public function refuse(
        int                    $id,
        EntityManagerInterface $em,
        InvitationRepository   $im
    ): Response
    {

        $invit = $im->find($id);
        $em->remove($invit);
        $em->flush();

        return $this->redirectToRoute('profile');
    }


    #[Route('profile/edit', name: 'profile.edit')]
    public function editProfile(
        Request                     $request,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $userPasswordHasher,
        #[CurrentUser] User         $currentUser
    ): Response
    {

        $user = $currentUser;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->persist($user);

            $em->flush();

            $this->addFlash('success', 'Vos informations ont bien été modifiées');
            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/edit.html.twig', [
            'form' => $form
        ]);
    }

}
