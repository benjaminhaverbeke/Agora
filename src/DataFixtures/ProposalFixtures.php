<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Proposals;
use App\Entity\Salons;
use App\Entity\Sujets;
use App\Entity\Votes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\SujetsRepository;
use Doctrine\ORM\EntityManagerInterface;


class ProposalFixtures extends Fixture
{


    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    )
    {

    }


    public function load(ObjectManager $manager): void
    {
        $user = (new User());
        $user->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@gmail.com')
            ->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($user, 'admin'));


        $manager->persist($user);


        $salon = new Salons();
        $salon
            ->setTitle('title')
            ->setDescription('description')
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setDateCampagne(new \DateTimeImmutable())
            ->setDateVote(new \DateTimeImmutable());


        $manager->persist($salon);


        for ($i = 0; $i < 10; $i++) {

            $sujet = new Sujets();
            $sujet->setTitle('title')
                ->setDescription('description')
                ->setSalon($salon)
                ->setUser($user);

            $manager->persist($sujet);


        }

        $manager->flush();

        $sm = $manager->getRepository(Sujets::class);
        $lastsujet = $sm->findLastInserted();

        for ($i = 0; $i < 4; $i++) {

            $proposals = new Proposals();

            $proposals
                ->setTitle('title' . $i)
                ->setDescription('description' . $i)
                ->setSujet($lastsujet)
                ->setSalon($salon)
                ->setUser($user);

            $manager->persist($proposals);
        }

        $manager->flush();

        $pm = $manager->getRepository(Proposals::class);
        $allprops = $pm->AllPropositionSujet($lastsujet->getId());

        $notesArray = ['passable', 'bien','tresbien'];
        $nbVotants = 5;

        foreach ($allprops as $prop) {

            for ($i = 0; $i < $nbVotants; $i++) {
                $vote = new Votes();
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
