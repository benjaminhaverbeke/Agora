<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Vote;
use App\Form\MessageType;
use App\Form\VoteType;
use App\Repository\ProposalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VoteController extends AbstractController
{
    #[Route('/vote/create/{id}', name: 'vote.create', requirements: ['id' => '\d+'])]
    public function index(int $id, Request $request, ProposalRepository $pm, EntityManagerInterface $em): Response
    {

        $proposal = $pm->find($id);
        $salon = $proposal->getSalon();
        $sujet = $proposal->getSujet();

        /***chat envoi message***/
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

        $messages = $sujet->getSalon()->getMessages();

        /******/


        $vote = new Vote();
        $voteForm = $this->createForm(VoteType::class, $vote);


        $voteForm->handleRequest($request);

        if ($voteForm->isSubmitted() && $voteForm->isValid()) {


            $vote->setSujet($sujet);
            $vote->setProposal($proposal);
            $vote->setNotes($request->get('notes'));


            $em->persist($proposal);

            $em->flush();

            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);

        }


        return $this->render('vote/create.html.twig', [
            'form' => $voteForm,
            'messages' => $messages,
            'messageForm' => $messageForm,
            'proposal' => $proposal,
            'salon' => $salon
        ]);

    }
}
