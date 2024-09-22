<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\Proposal;
use App\Entity\Sujet;
use App\Form\InvitType;
use App\Form\MessageType;
use App\Form\ProposalType;
use App\Form\SalonType;
use App\Entity\Salon;
use App\Entity\Message;
use App\Form\SujetType;
use App\Form\VoteType;
use App\Repository\InvitationRepository;
use App\Repository\MessageRepository;
use App\Repository\ProposalRepository;
use App\Repository\SalonRepository;
use App\Repository\SujetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\HttpClient\Response\MockResponse;


class SalonController extends AbstractController
{
    private $mm;
    private $sm;

    private $em;

    private $um;

    public function __construct(MessageRepository $mm, SalonRepository $sm, EntityManagerInterface $em, UserRepository $um)
    {

        $this->mm = $mm;
        $this->sm = $sm;
        $this->em = $em;
        $this->um = $um;
    }

    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(
        int                  $id,
        SujetRepository      $sujet,
        Request              $request,
        UserRepository       $um,
        ProposalRepository   $pm,
        InvitationRepository $im
    ): Response
    {

        $salon = $this->sm->findSalonIndex($id);

        /***chat envoi message***/
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);


        /******/

        /*** compte a rebours ***/

        $time = $this->timeProcess($salon);

        /*** invitation ***/

        $form = $this->createForm(InvitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->getData()["email"];
            $receiver = $um->findOneBy(['email' => $email]);

            $isInvited = $im->findByReceiverField($receiver);


            if ($receiver === null) {

                $this->addFlash('error-invit', "Utilisateur introuvable");


            } elseif (count($isInvited) > 1) {

                $this->addFlash('error-exist', 'Invitation déjà envoyée');
            } else {

                $sender = $this->getUser();
                $invit = new Invitation();

                $invit->setSalon($salon);
                $invit->setReceiver($receiver);
                $invit->setSender($sender);

                $this->em->persist($invit);
                $this->em->flush();
                $this->addFlash('success-invit', "Utilisateur invité");
            }


        }




//        $result = $election->isElected($lastsujet->getId());

//        $time_salon = $sm->timeProcess($salon);


        if($time['type'] === "campagne")
        {
            return $this->render('salon/index.html.twig', [
                'messageForm' => $messageForm,
                'salon' => $salon,
                'time' => $time,
                'form' => $form


            ]);

        }

        if($time['type'] === "vote")
        {


            return $this->render('salon/index.html.twig', [
                'messageForm' => $messageForm,
                'salon' => $salon,
                'time' => $time,
                'form' => $form,
            ]);


        }


        return $this->render('salon/index.html.twig', [
            'messageForm' => $messageForm,
            'salon' => $salon,
            'time' => $time,
            'form' => $form,



        ]);


    }

    #[Route('salon/{id}/edit', name: 'salon.edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {

        /***chat envoi message***/
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

        $messages = $this->mm->findBySalons($id);

        $salon = $this->sm->find($id);

        $form = $this->createForm(SalonType::class, $salon);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success-salon-edit', 'Les paramètres du salon ont bien été modifiés');
            return $this->redirectToRoute('salon.index', ['id' => $id]);
        }

        return $this->render('salon/edit.html.twig', [
            'messages' => $messages,
            'salon' => $salon,
            'form' => $form,
            'messageForm' => $messageForm
        ]);

    }

    #[Route('salon/create', name: 'salon.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $salon = new Salon();
        $salon->setUser($user);
        $salon->addUser($user);


        $form = $this->createForm(SalonType::class, $salon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $salon->setCreatedAt(new \DateTimeImmutable());


            $em->persist($salon);
            $em->flush();
            $this->addFlash('success-salon-create', 'Le salon a bien été crée');
            return $this->redirectToRoute('salon.index', ['id' => $salon->getId()]);
        }
        return $this->render('salon/create.html.twig', [
            'form' => $form
        ]);


    }

    #[Route('salon/{id}/delete', name: 'salon.delete')]
    public function delete(int $id, SalonRepository $salon, EntityManagerInterface $em): Response
    {

        $salonTodelete = $salon->find($id);

        $em->remove($salonTodelete);
        $em->flush();

        $this->addFlash('success-salon-delete', 'Le salon a bien été supprimé');

        return $this->redirectToRoute('profile');

    }

    #[Route('salon/list', name: 'salon.list')]
    public function salonlist(SujetRepository $sujm, SalonRepository $salm, Request $request): Response
    {

        $user = $this->getUser();
        $userId = $user->getId();
//

        /***pagination***/
        $limit = 2;
        $page = $request->query->getInt('page', 1);
        $salons = $salm->paginateSalons($userId, $page, $limit);
        $maxPage = ceil($salons->count() / $limit);


        $salonlist = [];

        foreach ($salons as $salon) {

            $sujets = $sujm->findAllSujetsBySalon($salon->getId());

            $salonTab["salon"] = $salon;
            $salonTab["nbUsers"] = count($salon->getUsers());
            $salonTab["nbSujets"] = count($sujets);

            $salonlist[] = $salonTab;
        }


        /***vérifie que l'utilisateur est connecté***/
        if (empty($user)) {
            {
                return $this->redirectToRoute('home');
            }

        } else {
            return $this->render('salon/list.html.twig', [

                'salonlist' => $salonlist,
                'maxPage' => $maxPage,
                'page' => $page
            ]);
        }

    }

    #[Route('salon/get-duration/{id}', name: "salon.get-duration", requirements: ['id' => '\d+'])]
    public function duration(int $id, SalonRepository $sm): JsonResponse
    {

        $salon = $sm->find($id);

        $time = $this->timeProcess($salon);

        return new JsonResponse($time);

    }


    public function timeProcess(Salon $salon): array
    {

        /*méthode qui
         * soit affiche le décompte fin de campagne,
         * soit affiche le décompte fin de vote,
         * soit autorise le résultat des votes (??a passer en paramètre de isElected ??)
         * */

        /***init variable qui stock le resultat***/

        $displaytime = [];

        /*prend le salon en parametre pour recuperer les dates*/

        $campagne = $salon->getDateCampagne();
        $vote = $salon->getDateVote();

        /*on stock la date actuelle*/

        $now = new \DateTimeImmutable('now');


        /***si la date actuelle est plus grande que la cloture de campagne
         * alors on affiche un décompte jusqu'à la fin de la camapgne**
         */


        if ($now < $campagne) {

            $interval = $campagne->diff($now);
            $displaytime["time"] = $campagne;

            $displaytime["time_message"] = "Temps de délibération restant :";
            $displaytime["type"] = "campagne";

        } elseif ($now < $vote) {

            $interval = $vote->diff($now);
            $displaytime["time"] = $vote;
            $displaytime["time_message"] = "Temps pour voter restant :";
            $displaytime["type"] = "vote";

        } else {

            $displaytime["time"] = "";
            $displaytime["time_message"] = "Temps terminé ! Merci d'avoir voté !";
            $displaytime["type"] = "results";
        }

        return $displaytime;
    }

    #[Route('salon/chat/{id}', name: "salon.chat", requirements: ['id' => '\d+'])]
    public function chat(request $request, int $id): Response
    {
        $salon = $this->sm->find($id);
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);


        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            $content = $messageForm->getData()->getContent();
            $message->setContent($content);
            $message->setUser($this->getUser());
            $message->setCreatedAt();
            $message->setSalon($salon);

            $this->em->persist($message);

            $this->em->flush();


            if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {

                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render(
                    'chat/message.stream.html.twig',
                    ["message" => $message]
                );
            }


        }

        return $this->redirectToRoute('salon.index', ["id" => $id]);


    }


}
