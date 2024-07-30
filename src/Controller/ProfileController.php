<?php

namespace App\Controller;

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

        $salons = $sm->findAll();
        $invits = $im->findByReceiverField($user);

        $form = $this->createForm(RejoinType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salonId = $form->getData()->getSalon()->getId();

            $invitation = $im->find($salonId);
            if (!$invitation) {
                // Gestion de l'erreur : invitation non trouvée
                return $this->redirectToRoute('profile');
            }


            $salon = $invitation->getSalon();
            $salon->addUser($user);

            // Supprimer l'invitation
            $em->remove($invitation);

            $em->flush();

            // Redirection vers le salon
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'salons' => $salons,
            'invits' => $invits,
            'form' =>$form
        ]);
    }
}
