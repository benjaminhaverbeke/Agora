<?php

namespace App\Controller;

use App\Entity\Proposals;
use App\Entity\Sujets;
use App\Form\ProposalType;
use App\Form\SujetType;
use App\Repository\ProposalsRepository;
use App\Repository\SujetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProposalController extends AbstractController
{

    #[Route('proposal/{id}', name: 'proposal.index', requirements: ['id' => '\d+'])]
    public function index(int $id, ProposalsRepository $pm): Response
    {
        $proposal = $pm->find($id);


        return $this->render('sujet/index.html.twig', [
            'proposal' => $proposal
        ]);
    }

    #[Route('/proposal/create/{id}', name: 'proposal.create', requirements: ['id' => '\d+'])]
    public function create(int $id, Request $request, EntityManagerInterface $em, SujetsRepository $sm): Response
    {

        $sujet = $sm->find($id);
        $salon = $sujet->getSalon();

        $user = $this->getUser();
        $proposal = new Proposals();

        $form = $this->createForm(ProposalType::class, $proposal);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $proposal->setUser($user);
            $proposal->setSalon($salon);
            $proposal->setSujet($sujet);

            $em->persist($proposal);

            $em->flush();
            $this->addFlash('success', 'La proposition a bien été crée');
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);

        }


        return $this->render('proposal/create.html.twig', [
            'form' => $form

        ]);
    }

    #[Route('proposal/{id}/edit', name: 'proposal.edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, SujetsRepository $sm, Request $request, Proposals $proposal, EntityManagerInterface $em): Response
    {
        $sujet = $proposal->getSujet();

        $salon = $sujet->getSalon();

        $form = $this->createForm(ProposalType::class, $proposal);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', "Les paramètres de la proposition ont bien été modifiés");
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }

        return $this->render('proposal/edit.html.twig', [
            'proposal' => $proposal,
            'form' =>$form
        ]);
    }


    #[Route('proposal/{id}/delete', name: 'proposal.delete')]
    public function delete(int $id, ProposalsRepository $pm, EntityManagerInterface $em): Response {

        $proposal = $pm->find($id);
        $salon = $proposal->getSujet()->getSalon();

        $em->remove($proposal);
        $em->flush();

        $this->addFlash('success', 'La proposition a bien été supprimé');

        return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
    }
}
