<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Proposals;
use App\Entity\Sujets;
use App\Form\MessageType;
use App\Form\ProposalType;
use App\Form\SujetType;
use App\Repository\MessagesRepository;
use App\Repository\ProposalsRepository;
use App\Repository\SujetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class ProposalController extends AbstractController
{
    private $mm;
    private $em;

    private $sujm;

    private $pm;

    public function __construct(MessagesRepository $mm, EntityManagerInterface $em, ProposalsRepository $pm, SujetsRepository $sujm)
    {
        $this->em = $em;
        $this->mm = $mm;
        $this->pm = $pm;
        $this->sujm = $sujm;
    }


    #[Route('/proposal/create/{id}', name: 'proposal.create', requirements: ['id' => '\d+'])]
    public function create(int $id, Request $request): Response
    {

        $sujet = $this->sujm->find($id);
        $salon = $sujet->getSalon();


        /***chat envoi message***/
        $message = new Messages();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

        $messages = $this->mm->findBySalons($salon->getId());

        /******/


        $user = $this->getUser();

        $proposal = new Proposals();
        $form = $this->createForm(ProposalType::class, $proposal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $proposal->setUser($user);

            $proposal->setSalon($salon);
            $proposal->setSujet($sujet);
            dump($proposal);
            $this->em->persist($proposal);

            $this->em->flush();

            $this->addFlash('success', 'La proposition a bien été crée');

            if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {

                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render(
                    'proposal/proposal.stream.html.twig',
                    [
                        "proposal" => $proposal,
                        "sujet" => $sujet
                    ]
                );
            } else {

                return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);


            }


        }


        return $this->render('proposal/create.html.twig', [
            'form' => $form,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'salon' => $salon,
            'sujet' => $sujet
        ]);
    }

    #[Route('proposal/{id}/edit', name: 'proposal.edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request): Response
    {

        $proposal = $this->pm->find($id);
        $salon = $proposal->getSalon();

        /***chat envoi message***/
        $message = new Messages();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

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
    public function delete(int $id, ProposalsRepository $pm, EntityManagerInterface $em): Response
    {

        $proposal = $pm->find($id);
        $salon = $proposal->getSujet()->getSalon();

        $em->remove($proposal);
        $em->flush();

        $this->addFlash('success', 'La proposition a bien été supprimé');

        return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
    }
}
