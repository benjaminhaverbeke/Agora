<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Proposal;
use App\Entity\Salon;
use App\Entity\Sujet;
use App\Entity\Vote;
use App\Repository\SalonRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;


class ProposalFixtures extends Fixture
{


    public function __construct(
        private readonly UserPasswordHasherInterface $hasher, UserRepository $um
    )
    {

    }


    public function load(ObjectManager $manager): void
    {
        $user = (new User());
        $user->setRoles(['ROLE_ADMIN'])
            ->setEmail('bundle@gmail.com')
            ->setUsername('bundle')
            ->setPassword($this->hasher->hashPassword($user, 'bundle'));


        $manager->persist($user);


        $salon = new Salon();
        $salon
            ->setTitle('title')
            ->setDescription('description')
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setDateCampagne(new \DateTimeImmutable())
            ->setDateVote(new \DateTimeImmutable())
            ->addUser($user);



        $manager->persist($salon);


        for ($i = 0; $i < 10; $i++) {

            $sujet = new Sujet();
            $sujet->setTitle('title')
                ->setDescription('description')
                ->setSalon($salon)
                ->setUser($user);

            $manager->persist($sujet);


        }

        $manager->flush();

        $sm = $manager->getRepository(Sujet::class);
        $lastsujet = $sm->findLastInserted();

        for ($i = 0; $i < 4; $i++) {

            $proposals = new Proposal();

            $proposals
                ->setTitle('title' . $i)
                ->setDescription('description' . $i)
                ->setSujet($lastsujet)
                ->setSalon($salon)
                ->setUser($user);

            $manager->persist($proposals);
        }

        $manager->flush();

        $pm = $manager->getRepository(Proposal::class);
        $allprops = $pm->AllPropositionSujet($lastsujet->getId());

        $notesArray = ['passable', 'bien','tresbien'];
        $nbVotants = 5;

        foreach ($allprops as $prop) {

            for ($i = 0; $i < $nbVotants; $i++) {
                $vote = new Vote();
                $randnote = array_rand($notesArray);
                $vote->setSujet($lastsujet)
                    ->setProposal($prop)
                    ->setNotes($notesArray[$randnote]);

                $manager->persist($vote);

            }


        }

        $manager->flush();
    }
}
