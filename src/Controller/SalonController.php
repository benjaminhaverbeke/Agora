<?php

namespace App\Controller;

use App\Form\SalonType;
use App\Entity\Salons;
use App\Repository\SalonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SalonController extends AbstractController
{
    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(Request $request, int $id, SalonsRepository $repository, EntityManagerInterface $em): Response
    {
        $salon = $repository->find($id);


        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'salon' => $salon,



        ]);
    }

    #[Route('/salon/{id}/edit', name: 'salon.edit', requirements: ['id' => '\d+'])]
    public function edit(Salons $salons, Request $request, EntityManagerInterface $em): Response {
        $form = $this->createForm(SalonType::class, $salons);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les paramètres du Salon ont bien été modifiés');
            return $this->redirectToRoute('salon.index', ['id' => $salons->getId()]);
        }
        return $this->render('salon/edit.html.twig', [
            'salons'=> $salons,
            'form' => $form
        ]);

    }

    #[Route('/salon/create', name: 'salon.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
            $salon = new Salons();
            $form = $this->createForm(SalonType::class);
            if($form->isSubmitted() && $form->isValid()) {
                $salon->setCreatedAt(new \DateTimeImmutable());
                $em->persist($salon);
                $em->flush();
                $this->addFlash('success', 'Le salon a bien été crée');
                return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
            }
           return $this->render('salon/create.html.twig', [
               'form' => $form
           ]);

    }


}
