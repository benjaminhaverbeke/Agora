<?php

namespace App\Controller;

use App\Form\InvitationType;
use App\Form\RejoinType;
use App\Repository\InvitationRepository;
use App\Repository\SalonsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(SalonsRepository $sm, InvitationRepository $im, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $salons = $user->getSalons();
        $invits = $im->findByReceiverField($user);


        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'salons' => $salons,
            'invits' => $invits,
        ]);
    }

    #[Route('/profile/invit/{id}', name: 'profile.invit', requirements: ['id' => '\d+'])]
    public function invit(int $id, EntityManagerInterface $em, InvitationRepository $im): Response
    {
        $user = $this->getUser();
        $invit = $im->find($id);

        $salon = $invit->getSalon();
        $salon->addUser($user);

        $em->persist($salon);

        $em->remove($invit);

        $em->flush();


        return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);

    }

    #[Route('/profile/invit/{id}/refuse', name: 'profile.refuse', requirements: ['id' => '\d+'])]
    public function refuse(int $id, EntityManagerInterface $em, InvitationRepository $im): Response
    {

        $invit = $im->find($id);
        $salon = $invit->getSalon();

        $em->remove($invit);

        $em->flush();

        return $this->redirectToRoute('profile');
    }

}
