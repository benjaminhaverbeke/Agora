<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Salons;
use App\Entity\Sujets;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\SujetType;
use App\Repository\MessagesRepository;
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
use Symfony\UX\Turbo\TurboBundle;

class SujetController extends AbstractController
{
    private $mm;
    private $em;

    private $sujm;

    private $sm;

    public function __construct(EntityManagerInterface $em, MessagesRepository $mm, SujetsRepository $sujm, SalonsRepository $sm)
    {
        $this->em = $em;
        $this->mm = $mm;
        $this->sujm = $sujm;
        $this->sm = $sm;

    }
    #[Route('sujet/{id}', name: 'sujet.index', requirements: ['id' => '\d+'])]
    public function index(int $id, Request $request): Response
    {
        $sujet = $this->sujm->find($id);
        $salonId = $sujet->getSalon()->getId();

        /***chat envoi message***/
        $message = new Messages();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

        $messages = $this->mm->findBySalons($salonId);

        /******/

        return $this->render('sujet/proposal.html.twig', [
            'sujet',
            'messages' => $messages,

        ]);
    }

    #[Route('sujet/{id}/edit', name: 'sujet.edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request): Response
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

        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', "Les paramètres du sujet ont bien été modifiés");
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }

        return $this->render('sujet/edit.html.twig', [
            'sujet' =>$sujet,
            'form' =>$form,
            'messages'=> $messages,
            'messageForm' => $messageForm,
            'salon' => $salon
        ]);
    }

    #[Route('sujet/create/{id}', name: 'sujet.create', requirements: ['id' => '\d+'])]
    public function create(Request $request, int $id): Response
    {
        $salon = $this->sm->find($id);

        /***chat envoi message***/
        $message = new Messages();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

        $messages = $this->mm->findBySalons($id);

        /******/

        $sujet = new Sujets();

        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $sujet->setSalon($salon);
            $user = $this->getUser();
            $sujet->setUser($user);
            $this->em->persist($sujet);
            $this->em->flush();
            $this->addFlash('success', 'Le sujet a bien été crée');
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }
        return $this->render('sujet/create.html.twig', [
            'form' => $form,
            'messages'=> $messages,
            'messageForm' => $messageForm,
            'salon' => $salon

        ]);

    }

    #[Route('sujet/{id}/delete', name: 'sujet.delete')]
    public function delete(int $id, SujetsRepository $sujet, EntityManagerInterface $em, Request $request): Response {


            $sujetTodelete = $sujet->find($id);

            $em->remove($sujetTodelete);
            $em->flush();

            $this->addFlash('success', 'Le sujet a bien été supprimé');

        if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render(
                'sujet/delete.stream.html.twig',
                [
                    "sujet" => $sujetTodelete
                ]
            );
        }
            return $this->redirectToRoute('salon.index', ['id' => $sujetTodelete->getSalon()->getId()]);
    }

}
