<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\Proposal;
use App\Entity\Sujet;
use App\Entity\User;
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
use App\Service\ElectionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\HttpClient\Response\MockResponse;


class SalonController extends AbstractController
{
    private $mm;
    private $sm;

    private $em;


    public function __construct(MessageRepository $mm, SalonRepository $sm, EntityManagerInterface $em)
    {

        $this->mm = $mm;
        $this->sm = $sm;
        $this->em = $em;

    }

    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(
        int                    $id,
        SujetRepository        $sujm,
        Request                $request,
        UserRepository         $um,
        ProposalRepository     $pm,
        InvitationRepository   $im,
        EntityManagerInterface $em,
        ElectionManager        $election,
        #[CurrentUser] User    $currentUser
    ): Response
    {


        $salon = $this->sm->findSalonIndex($id);


        $users = $salon->getUsers();

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            $this->addFlash('error', "Vous n'avez pas accès à cette assemblée");
            return $this->redirectToRoute('home');
        }


        /***chat envoi message***/
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);


        /******/

        /*** compte a rebours ***/

        $time = $this->timeProcess($salon);

        /*** invitation ***/

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Email de l'utilisateur"
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => "btn"
                ]
            ])->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->getData()["email"];

            $receiver = $um->findOneBy(['email' => $email]);

            if ($receiver === null) {

                if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {


                    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                    return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'introuvable', 'form' => $form]);
                }


            } else {

                $invitations = $salon->getInvitations();
                $isInvited = $invitations->filter(function ($key, $value) use ($receiver) {
                    return $value === $receiver;
                });


                if (count($isInvited) > 0) {

                    if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {


                        // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                        return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'exist', 'form' => $form]);
                    }


                } else {
                    $sender = $currentUser;
                    $invit = new Invitation();

                    $invit->setSalon($salon);
                    $invit->setReceiver($receiver);
                    $invit->setSender($sender);
                    $salon->addInvitation($invit);

                    $this->em->persist($salon);
                    $this->em->persist($invit);
                    $this->em->flush();


                    if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {


                        // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                        return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'valid', 'form' => $form]);
                    }
                }


            }


        }

        /*********PHASE VOTE************/


        if ($time['type'] === 'vote') {


//            $user = $currentUser;
//            $voted = $user->getVoted();
//            $voted->clear();
//
//            $em->persist($user);
//
//            $em->flush();


            $sujetIsVoted = [];


            $sujets = $salon->getSujets();

            foreach ($sujets as $sujet) {


                $voters = $sujet->getVoters();

//                $voters->clear();
//
//                $em->persist($sujet);
//                $em->flush();

                $userhasVoted = $voters->exists(function ($key, $value) use ($currentUser) {

                    if ($value === $currentUser) {
                        return $value;
                    }

                    return false;

                });


                if ($userhasVoted === false) {

                    $sujetIsVoted[] = [
                        'voted' => false,
                        'sujet' => $sujet
                    ];

                } else {

                    $votes = $currentUser->getVotes();


                    $result = $votes->filter(function ($element, $sujet) {

                        if ($element->getSujet($sujet)) {

                            return $element;
                        }


                    });


                    $sujetIsVoted[] = [
                        'voted' => true,
                        'sujet' => $sujet,
                        'votes' => $result
                    ];
                }


            }


            return $this->render('salon/index.html.twig', [
                'messageForm' => $messageForm,
                'salon' => $salon,
                'time' => $time,
                'form' => $form,
                'sujetIsVoted' => $sujetIsVoted


            ]);

        } elseif ($time["type"] === "results") {

            $sujets = $salon->getSujets();


            $results = [];

            foreach ($sujets as $sujet) {

                $result = $election->isElected($sujet->getId());

                $results[] = [
                    'sujet' => $sujet,
                    'result' => $result

                ];


            }


            return $this->render('salon/index.html.twig', [
                'messageForm' => $messageForm,
                'salon' => $salon,
                'time' => $time,
                'form' => $form,
                'results' => $results


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
    public function create(Request $request, EntityManagerInterface $em, #[CurrentUser] User $currentUser): Response
    {


        $salon = new Salon();
        $salon->setUser($currentUser);
        $salon->addUser($currentUser);


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
    public function delete(int $id, SalonRepository $sm, EntityManagerInterface $em): Response
    {

        $salon = $sm->findSalonIndex($id);
        $em->remove($salon);

        $em->flush();

        $this->addFlash('success-salon-delete', 'Le salon a bien été supprimé');

        return $this->redirectToRoute('profile');

    }

    #[Route('salon/list', name: 'salon.list')]
    public function salonlist(SujetRepository $sujm, SalonRepository $salm, Request $request, #[CurrentUser] User $currentUser): Response
    {


        /***pagination***/
        $limit = 2;
        $page = $request->query->getInt('page', 1);
        $salons = $salm->paginateSalons($currentUser->getId(), $page, $limit);
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


        return $this->render('salon/list.html.twig', [

            'salonlist' => $salonlist,
            'maxPage' => $maxPage,
            'page' => $page
        ]);


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
    public function chat(request $request, int $id, #[CurrentUser] User $currentUser): Response
    {
        $salon = $this->sm->find($id);
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);


        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            $content = $messageForm->getData()->getContent();
            $message->setContent($content);
            $message->setUser($currentUser);

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

    #[Route('salon/get-results/{id}', name: "salon.get-results", requirements: ['id' => '\d+'])]
    public function results(int $id, SujetRepository $sujm, ElectionManager $election): JsonResponse
    {

        $sujet = $sujm->find($id);

        $result = $election->isElected($sujet->getId());



        return new JsonResponse($result);

    }


}
