<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\SalonType;
use App\Entity\Salon;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\SalonRepository;
use App\Repository\SujetRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Service\ElectionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;


class SalonController extends AbstractController
{
    private MessageRepository $mm;
    private SalonRepository $sm;
    private EntityManagerInterface $em;
    private ElectionManager $electionManager;


    public function __construct(MessageRepository $mm, SalonRepository $sm, EntityManagerInterface $em, ElectionManager $electionManager)
    {

        $this->mm = $mm;
        $this->sm = $sm;
        $this->em = $em;
        $this->electionManager = $electionManager;

    }

    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(
        int                 $id,
        Request             $request,
        UserRepository      $um,
        #[CurrentUser] User $currentUser,
        VoteRepository      $vm,
        SujetRepository     $sujm,
    ): Response
    {

        $salon = $this->sm->findSalonIndex($id);
        $users = $salon->getUsers();

        /***check user access, if user exists in salon users collection***/

        $hasAccess = $users->exists(function ($key, $value) use ($currentUser) {
            return $value === $currentUser;
        });

        if (!$hasAccess) {

            $this->addFlash('error', "Vous n'avez pas accès à cette assemblée");
            return $this->redirectToRoute('home');
        }


        /***CHAT***/

        /***initialize chat form***/

        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /*****/


        /***COUNTDOWN***/

        $time = $this->timeProcess($salon);

        /***INVITATION***/

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

            /***if user not found, turbo injects a block containing warning message***/
            if ($receiver === null) {

                /***to use Turbostream, request need to accept turbo format***/
                if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {

                    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                    return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'introuvable', 'form' => $form]);
                }


            } else {

                /***same process if user is already invited***/

                $invitations = $salon->getInvitations();
                $isInvited = $invitations->filter(function ($key, $value) use ($receiver) {
                    return $value === $receiver;
                });

                if (count($isInvited) > 0) {

                    if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {

                        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                        return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'exist', 'form' => $form]);
                    }

                } else {

                    /***check if user is already a salon member***/

                    $users = $salon->getUsers();
                    $alreadyInSalon = $users->filter(function ($key, $value) use ($receiver) {
                        return $value === $receiver;
                    });

                    if (count($alreadyInSalon) !== 0) {

                        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {

                            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                            return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'already', 'form' => $form]);
                        }
                    }

                    /***else an invitation is instanciated***/

                    $sender = $currentUser;
                    $invit = new Invitation();

                    $invit->setSalon($salon);
                    $invit->setReceiver($receiver);
                    $invit->setSender($sender);

                    /***not forgeting to add it in salon collection***/
                    $salon->addInvitation($invit);

                    $this->em->persist($salon);
                    $this->em->persist($invit);
                    $this->em->flush();

                    /***display success message***/
                    if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {

                        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                        return $this->renderBlock('partials/invit_flash.stream.html.twig', 'success_stream', ["invit" => 'valid', 'form' => $form]);
                    }
                }

            }

        }


        /********************************************/
        /*                VOTE PHASE                */
        /********************************************/


        if ($time['type'] === 'vote') {


            /***need to check if user has already voted to give access to vote form or to vote answers***/

            $sujetIsVoted = [];

            $sujets = $sujm->findSujetsWithCollections($salon->getId());

            foreach ($sujets as $sujet) {

                $voters = $sujet->getVoters();

                $userhasVoted = $voters->exists(function ($key, $value) use ($currentUser) {

                    if ($value === $currentUser) {
                        return $value;
                    }

                    return false;

                });

                /***This boolean is used to display information according to the context***/

                if ($userhasVoted === false) {

                    $sujetIsVoted[] = [
                        'voted' => false,
                        'sujet' => $sujet
                    ];

                } else {

                    $votes = $vm->findVotesOnSujetByUser($currentUser->getId(), $sujet->getId());


                    $sujetIsVoted[] = [
                        'voted' => true,
                        'sujet' => $sujet,
                        'votes' => $votes
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


            /*****************************************/
            /*               RESULTS                 */
            /*****************************************/

        } elseif ($time["type"] === "results") {

            $sujets = $salon->getSujets();

            $results = [];

            /***getting results for each sujet***/

            foreach ($sujets as $sujet) {

                $result = $this->electionManager->isElected($sujet);

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

        /********************************************************/
        /*                    CAMPAGNE                          */
        /********************************************************/


        /***controller is rendering a default block if time type is campagne***/

        return $this->render('salon/index.html.twig', [
            'messageForm' => $messageForm,
            'salon' => $salon,
            'time' => $time,
            'form' => $form,
        ]);
    }

    #[Route('salon/{id}/edit', name: 'salon.edit', requirements: ['id' => '\d+'])]
    public function edit(
        int                    $id,
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {

        $salon = $this->sm->findSalonWithMessages($id);

        /***CHAT***/

        /***chat form***/
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);

        /***chat display messages***/

        $messages = $this->mm->findBySalons($id);

        /****/

        /****create salon form***/

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
    public function create(
        Request                $request,
        EntityManagerInterface $em,
        #[CurrentUser] User    $currentUser
    ): Response
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
    public function delete(
        int                    $id,
        EntityManagerInterface $em
    ): Response
    {
        $salon = $this->sm->find($id);

        $em->remove($salon);

        $em->flush();

        $this->addFlash('success-salon-delete', 'Le salon a bien été supprimé');

        return $this->redirectToRoute('profile');

    }

    #[Route('salon/list', name: 'salon.list')]
    public function salonlist(
        SujetRepository     $sujm,
        SalonRepository     $salm,
        Request             $request,
        #[CurrentUser] User $currentUser
    ): Response
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

        return $this->render('salon/list.html.twig', [

            'salonlist' => $salonlist,
            'maxPage' => $maxPage,
            'page' => $page
        ]);

    }


    #[Route('salon/get-duration/{id}', name: "salon.get-duration", requirements: ['id' => '\d+'])]
    public function duration(
        int             $id,
        SalonRepository $sm
    ): JsonResponse
    {

        $salon = $sm->find($id);
        $time = $this->timeProcess($salon);
        return new JsonResponse($time);

    }

    public function timeProcess(Salon $salon): array
    {

        /***This internal API allows to retrieve the salon's deadlines***/

        $displaytime = [];

        $campagne = $salon->getDateCampagne();
        $vote = $salon->getDateVote();

        /***stocking current date to compare***/

        $now = new \DateTimeImmutable('now');

        if ($now < $campagne) {

            $displaytime["time"] = $campagne;
            $displaytime["time_message"] = "Temps de délibération restant : ";
            $displaytime["type"] = "campagne";

        } elseif ($now < $vote) {

            $displaytime["time"] = $vote;
            $displaytime["time_message"] = "Temps pour voter restant : ";
            $displaytime["type"] = "vote";

        } else {

            $displaytime["time"] = "";
            $displaytime["time_message"] = "Temps terminé ! Merci d'avoir voté !";
            $displaytime["type"] = "results";
        }

        return $displaytime;
    }

    #[Route('salon/chat/{id}', name: "salon.chat", requirements: ['id' => '\d+'])]
    public function chat(
        int                 $id,
        Request             $request,
        #[CurrentUser] User $currentUser,
        HubInterface $hub
    ): Response
    {
        /***processing chat requests***/

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

            $update = new Update(
                'salon/'.$id.'/chat',
                json_encode([
                    'message' => $content,
                    'author' => $currentUser->getUsername(),
                    'date' => date_format($messageForm->getData()->getCreatedAt(), 'G:i')
                ])
            );

            $hub->publish($update);

            /***message block is streamed***/

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
    public function results(
        int $id,
        SujetRepository $sujm,
        ElectionManager $election
    ): JsonResponse
    {
        /***internal api to get results***/

        $sujet = $sujm->find($id);
        $result = $election->isElected($sujet);

        return new JsonResponse($result);
    }

    #[Route('salon/publish/{id}', name: "salon.publish", requirements: ['id' => '\d+'])]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'https://localhost:3000/books/1',
            json_encode(['status' => 'message reçu'])
        );

        $hub->publish($update);

        return new Response('published!');
    }



}
