<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Proposal;
use App\Entity\Sujet;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\ProposalType;
use App\Form\SujetType;
use App\Repository\MessageRepository;
use App\Repository\ProposalRepository;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\Turbo\TurboBundle;

class ProposalController extends AbstractController
{
    private MessageRepository $mm;
    private EntityManagerInterface $em;
    private SujetRepository $sujm;
    private ProposalRepository $pm;

    public function __construct(
        MessageRepository $mm, EntityManagerInterface $em, ProposalRepository $pm, SujetRepository $sujm)
    {
        $this->em = $em;
        $this->mm = $mm;
        $this->pm = $pm;
        $this->sujm = $sujm;
    }


    #[Route('/proposal/create/{id}', name: 'proposal.create', requirements: ['id' => '\d+'])]
    public function create(
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

        /***message form***/

        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***display messages***/

        $messages = $sujet->getSalon()->getMessages();

        /******/

        $proposal = new Proposal();


        $form = $this->createForm(ProposalType::class, $proposal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $proposal->setUser($sujet->getSalon()->getUser());
            $proposal->setSalon($sujet->getSalon());
            $proposal->setSujet($sujet);
            $sujet->addProposal($proposal);

            $this->em->persist($proposal);

            $this->em->flush();

            $this->addFlash('success', 'La proposition a bien été crée');


            return $this->redirectToRoute('salon.index', ['id' => $sujet->getSalon()->getId()]);

        }


        return $this->render('proposal/create.html.twig', [
            'form' => $form,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'sujet' => $sujet,
            'salon' => $salon
        ]);
    }

    #[Route('proposal/{id}/edit', name: 'proposal.edit', requirements: ['id' => '\d+'])]
    public function edit(
        int                 $id,
        Request             $request,
        #[CurrentUser] User $currentUser
    ): Response
    {

        $proposal = $this->pm->find($id);
        $salon = $proposal->getSalon();

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

        /***messages display***/

        $messages = $this->mm->findBySalons($salon->getId());

        /******/


        $form = $this->createForm(ProposalType::class, $proposal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($proposal);

            $this->em->flush();

            $this->addFlash('success-proposal-edit', "Les paramètres de la proposition ont bien été modifiés");

            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }

        return $this->render('proposal/edit.html.twig', [
            'proposal' => $proposal,
            'form' => $form,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'salon' => $salon
        ]);
    }


    #[Route('proposal/{id}/delete', name: 'proposal.delete')]
    public function delete(
        int                    $id,
        EntityManagerInterface $em,
        #[CurrentUser] User    $currentUser
    ): Response
    {
        $proposal = $this->pm->find($id);
        $salon = $proposal->getSalon();
        $users = $salon->getUsers();

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            return $this->redirectToRoute('home');

        }


        $em->remove($proposal);
        $em->flush();

        $this->addFlash('success', 'La proposition a bien été supprimée');


        return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
    }
}
