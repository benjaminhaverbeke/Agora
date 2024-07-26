<?php

namespace App\Controller;

use App\Form\SalonType;
use App\Entity\Salons;
use App\Entity\User;
use App\Repository\ProposalsRepository;
use App\Repository\SalonsRepository;
use App\Repository\SujetsRepository;
use App\Repository\VotesRepository;
use App\Service\ElectionManager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SalonController extends AbstractController
{


    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(
                          int                    $id,
                          SujetsRepository       $sujet,
                          ProposalsRepository    $pm,
                          SalonsRepository       $sm,
                          VotesRepository        $vm,
                          ElectionManager        $election,
                          EntityManagerInterface $em): Response
    {

        $lastsujet = $sujet->findLastInserted();

        $salon = $sm->find($id);


        $allsujets = $sujet->findAllSujetsBySalon($salon->getId());
//        $result = $election->isElected($lastsujet->getId());

//        $time_salon = $sm->timeProcess($salon);


        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'salon' => $salon,
            'allsujets' => $allsujets

        ]);
    }

    #[Route('salon/{id}/edit', name: 'salon.edit', requirements: ['id' => '\d+'])]
    public function edit(Salons $salons, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SalonType::class, $salons);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les paramètres du salon ont bien été modifiés');
            return $this->redirectToRoute('salon.index', ['id' => $salons->getId()]);
        }
        return $this->render('salon/edit.html.twig', [
            'salons' => $salons,
            'form' => $form
        ]);

    }

    #[Route('salon/create', name: 'salon.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $salon = new Salons();
        $salon->setUser($user);
        $salon->addUser($user);


        $form = $this->createForm(SalonType::class, $salon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
    public function delete(int $id, SalonsRepository $salon, EntityManagerInterface $em): Response
    {

        $salonTodelete = $salon->find($id);

        $em->remove($salonTodelete);
        $em->flush();

        $this->addFlash('success', 'Le salon a bien été supprimé');

        return $this->redirectToRoute('profile');

    }

    #[Route('salon/list', name: 'salon.list', requirements: ['id' => '\d+'])]
    public function salonlist_index():Response
    {

            $session_user = $this->getUser();
            $collection = $session_user->getSalons();

            $salonlist = [];
                foreach($collection as $value){

                $salonlist[] = $value;

                }

        if(empty($session_user)){
            {
                return $this->redirectToRoute('home');
            }

        }
        else{
            return $this->render('salon/list.html.twig', [

                'salonlist' => $salonlist
            ]);
        }

    }

}
