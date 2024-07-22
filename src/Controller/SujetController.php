<?php

namespace App\Controller;

use App\Entity\Salons;
use App\Entity\Sujets;
use App\Entity\User;
use App\Form\SujetType;
use App\Repository\SalonsRepository;
use App\Repository\SujetsRepository;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SujetController extends AbstractController
{
    #[Route('sujet/{id}', name: 'sujet.index', requirements: ['id' => '\d+'])]
    public function index(int $id, SujetsRepository $sujet): Response
    {
        $sujet = $sujet->find($id);


        return $this->render('sujet/index.html.twig', [
            'controller_name' => 'SujetController',
            'sujet'
        ]);
    }

    #[Route('sujet/{id}/edit', name: 'sujet.edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Sujets $sujet, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', "Les paramètres du sujet ont bien été modifiés");
            return $this->redirectToRoute('salon.index', ['id' => $sujet->getSalon()->getId()]);
        }

        return $this->render('sujet/edit.html.twig', [
            'sujet' =>$sujet,
            'form' =>$form
        ]);
    }

    #[Route('sujet/create/{id}', name: 'sujet.create')]
    public function create(Request $request, int $id, SalonsRepository $salonsRepository, EntityManagerInterface $em): Response
    {
        $salon = $salonsRepository->find($id);


        $sujet = new Sujets();

        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $sujet->setSalon($salon);
            $user = $this->getUser();
            $sujet->setUser($user);
            $em->persist($sujet);
            $em->flush();
            $this->addFlash('success', 'Le sujet a bien été crée');
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }
        return $this->render('sujet/create.html.twig', [
            'form' => $form

        ]);

    }

    #[Route('sujet/{id}/delete', name: 'sujet.delete')]
    public function delete(int $id, SujetsRepository $sujet, EntityManagerInterface $em): Response {

            $sujetTodelete = $sujet->find($id);

            $em->remove($sujetTodelete);
            $em->flush();

            $this->addFlash('success', 'Le sujet a bien été supprimé');

            return $this->redirectToRoute('salon.index', ['id' => $sujetTodelete->getSalon()->getId()]);
    }

}
