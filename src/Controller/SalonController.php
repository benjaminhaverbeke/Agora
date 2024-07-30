<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Form\InvitType;
use App\Form\SalonType;
use App\Entity\Salons;
use App\Entity\User;
use App\Repository\InvitationRepository;
use App\Repository\ProposalsRepository;
use App\Repository\SalonsRepository;
use App\Repository\SujetsRepository;
use App\Repository\UserRepository;
use App\Repository\VotesRepository;
use App\Service\ElectionManager;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SalonController extends AbstractController
{


    #[Route('/salon/{id}', name: 'salon.index', requirements: ['id' => '\d+'])]
    public function index(
        int              $id,
        SujetsRepository $sujet,
        Request          $request,
        SalonsRepository $sm,
        UserRepository   $um,
        EntityManagerInterface $em,
        InvitationRepository $im,
    ): Response
    {

        $salon = $sm->find($id);

        $time = $this->timeProcess($salon);


        $form = $this->createForm(InvitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData();
            $receiver = $um->findOneBy(['email' => $email]);

            if($receiver === null){

                $this->addFlash('error', "Utilisateur introuvable");
            }
            else{

                $sender = $this->getUser();
                $invit = new Invitation($sender, $receiver, $salon);

                $em->persist($invit);
                $em->flush();
                $this->addFlash('success', "L'utilisateur a bien été invité sur le salon");
            }


        }

        if ($time)

            $allsujets = $sujet->findAllSujetsBySalon($salon->getId());
//        $result = $election->isElected($lastsujet->getId());

//        $time_salon = $sm->timeProcess($salon);


        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'salon' => $salon,
            'allsujets' => $allsujets,
            'time' => $time,
            'form' => $form


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
    public function salonlist_index(): Response
    {

        $session_user = $this->getUser();
        $collection = $session_user->getSalons();

        $salonlist = [];
        foreach ($collection as $value) {

            $salonlist[] = $value;

        }

        if (empty($session_user)) {
            {
                return $this->redirectToRoute('home');
            }

        } else {
            return $this->render('salon/list.html.twig', [

                'salonlist' => $salonlist
            ]);
        }

    }

    #[Route('salon/get-duration/{id}', name: "salon.get-duration", requirements: ['id' => '\d+'])]
    public function duration(int $id, SalonsRepository $sm): JsonResponse
    {

        $salon = $sm->find($id);

        if (!$salon) {

            throw $this->createNotFoundException('Salon non trouvé');

        }
        try {
            $time = $this->timeProcess($salon);
            return new JsonResponse(['duration' => $time]);


        } catch (\Exception $e) {
            throw $this->createNotFoundException('Problème dans le calcul du temps');
        }


    }

    #[Route('salon/invit/{id}', name: "salon.invit", requirements: ['id' => '\d+'])]
    public function invit(int $id, EntityManagerInterface $em, UserRepository $um, Request $request): Response
    {
        $form = $this->createForm(InvitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "L'utilisateur a bien été invité sur le salon");
            return $this->redirectToRoute('salon.index', ['id' => $id]);
        } else {
            $this->addFlash('error', "Utilisateur introuvable");
        }

        return $this->render('salon/invit.html.twig', [

        ]);

    }

    private function timeProcess(Salons $salon): array
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
            $displaytime["time"] = $interval->format("%H:%I:%S (Jours restants: %a)");
            $displaytime["time_message"] = "Temps de délibération restant :";
            $displaytime["type"] = "campagne";

        } elseif ($now < $vote) {

            $interval = $vote->diff($now);
            $displaytime["time"] = $interval->format("%H:%I:%S (Jours restants: %a)");
            $displaytime["time_message"] = "Temps pour voter restant :";
            $displaytime["type"] = "vote";

        } else {

            $displaytime["time"] = "";
            $displaytime["time_message"] = "Temps terminé ! Merci d'avoir voté !";
            $displaytime["type"] = "results";
        }

        return $displaytime;
    }


}
