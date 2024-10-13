<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Sujet;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\SujetType;
use App\Repository\MessageRepository;
use App\Repository\SalonRepository;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\Vote;


class SujetController extends AbstractController
{
    private MessageRepository $mm;
    private EntityManagerInterface $em;
    private SujetRepository $sujm;
    private SalonRepository $sm;

    public function __construct(EntityManagerInterface $em, MessageRepository $mm, SujetRepository $sujm, SalonRepository $sm)
    {
        $this->em = $em;
        $this->mm = $mm;
        $this->sujm = $sujm;
        $this->sm = $sm;

    }


    #[Route('sujet/{id}/edit', name: 'sujet.edit', requirements: ['id' => '\d+'])]
    public function edit(
        int                 $id,
        Request             $request,
        #[CurrentUser] User $currentUser
    ): Response
    {
        $sujet = $this->sujm->find($id);

        $salon = $sujet->getSalon();
        $users = $salon->getUsers();

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            return $this->redirectToRoute('home');
        }

        /***CHAT***/

        /***chat form***/

        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***display messages***/

        $messages = $this->mm->findBySalons($salon->getId());

        /******/

        $form = $this->createForm(SujetType::class, $sujet, ['vote' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $this->em->flush();
            $this->addFlash('success', "Les paramètres du sujet ont bien été modifiés");
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }

        return $this->render('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'salon' => $salon
        ]);
    }

    #[Route('sujet/create/{id}', name: 'sujet.create', requirements: ['id' => '\d+'])]
    public function create(
        Request $request,
        int     $id, #[CurrentUser] User $currentUser
    ): Response
    {
        $salon = $this->sm->find($id);

        $users = $salon->getUsers();

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            return $this->redirectToRoute('home');
        }

        /***CHAT***/

        /***chat form***/

        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat messages***/

        $messages = $this->mm->findBySalons($id);

        /******/

        $sujet = new Sujet();

        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sujet->setSalon($salon);
            $user = $this->getUser();
            $sujet->setUser($user);
            $salon->getSujets()->add($sujet);
            $this->em->persist($sujet);

            $this->em->flush();
            $this->addFlash('success', 'Le sujet a bien été crée');
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }
        return $this->render('sujet/create.html.twig', [
            'form' => $form,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'salon' => $salon

        ]);

    }


    #[Route('sujet/{id}/delete', name: 'sujet.delete')]
    public function delete(
        int                    $id,
        SujetRepository        $sujet,
        EntityManagerInterface $em,
        #[CurrentUser] User    $currentUser
    ): Response
    {

        $sujetTodelete = $sujet->find($id);
        $salon = $sujetTodelete->getSalon();

        $users = $salon->getUsers();

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            return $this->redirectToRoute('home');
        }

        $em->remove($sujetTodelete);
        $em->flush();

        $this->addFlash('success', 'Le sujet a bien été supprimé');

        return $this->redirectToRoute('salon.index', ['id' => $sujetTodelete->getSalon()->getId()]);
    }


    #[Route('sujet/{id}/vote', name: 'sujet.vote', requirements: ['id' => '\d+'])]
    public function vote(
        int                    $id,
        Request                $request,
        EntityManagerInterface $em,
        #[CurrentUser] User    $currentUser
    ): Response
    {

        $sujet = $this->sujm->findSujetWithProposalsAndSalonUsers($id);
        $salon = $sujet->getSalon();
        $users = $salon->getUsers();

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            return $this->redirectToRoute('home');

        }

        /******CHAT******/

        /***message form***/

        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***messages collection***/

        $messages = $salon->getMessages();

        /****************/

        $proposals = $sujet->getProposals();

        foreach ($proposals as $proposal) {

            $vote = new Vote();
            $vote->setProposal($proposal);
            $vote->setUser($currentUser);
            $vote->setSujet($sujet);
            $proposal->addVote($vote);
        }


        $form = $this->createForm(SujetType::class, $sujet, ['vote' => true]);


        $form->handleRequest($request);

        $submittedToken = $request->getPayload()->get('token');

        $token = $this->isCsrfTokenValid('vote', $submittedToken);

        if ($form->isSubmitted() && $token) {


            $sujet->addVoter($currentUser);
            $currentUser->addVoted($sujet);
            $em->persist($sujet);
            $em->persist($currentUser);

            $this->em->flush();
            $this->addFlash('success', "Les votes ont bien été pris en compte");
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }

        return $this->render('sujet/vote.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'salon' => $salon
        ]);
    }


}
