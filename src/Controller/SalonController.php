<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\SalonType;
use App\Entity\Salons;
use App\Entity\Sujets;
use App\Form\SujetType;
use App\Repository\ProposalsRepository;
use App\Repository\SalonsRepository;
use App\Repository\SujetsRepository;
use App\Repository\VotesRepository;
use App\Service\ElectionManager;
use App\Service\MentionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SalonController extends AbstractController
{
    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(Request $request, int $id,
                          SujetsRepository $sujet,
                          ProposalsRepository $pm,
                          SalonsRepository $salonRepository,
                          VotesRepository $vm,
                          ElectionManager $election,
                          EntityManagerInterface $em): Response
    {

        $salon = $salonRepository->find($id);
        $allSujets = $sujet->findAll();
        $props = $pm->AllPropositionSujet(185);
        $result = $election->allMentionsByProposal($props);
        $winners = $election->allWinnerMentions($result);
        $thewinner = $election->departageMentions($winners);


        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'salon' => $salon,
            'allsujets' => $allSujets



        ]);
    }

    #[Route('salon/{id}/edit', name: 'salon.edit', requirements: ['id' => '\d+'])]
    public function edit(Salons $salons, Request $request, EntityManagerInterface $em): Response {
        $form = $this->createForm(SalonType::class, $salons);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les paramètres du salon ont bien été modifiés');
            return $this->redirectToRoute('salon.index', ['id' => $salons->getId()]);
        }
        return $this->render('salon/edit.html.twig', [
            'salons'=> $salons,
            'form' => $form
        ]);

    }

    #[Route('salon/create', name: 'salon.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
            $salon = new Salons();
            $form = $this->createForm(SalonType::class, $salon);


            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $user = $this->getUser();
                $salon->setUser($user);
                $salon->setPrivacy('PRIVATE');
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

    #[Route('salon/{id}/delete', name: 'salon.delete')]
    public function delete(int $id, SalonsRepository $salon, EntityManagerInterface $em) : Response {

        $salonTodelete = $salon->find($id);

        $em->remove($salonTodelete);
        $em->flush();

        $this->addFlash('success', 'Le salon a bien été supprimé');

        return $this->redirectToRoute('profile');

    }

}
