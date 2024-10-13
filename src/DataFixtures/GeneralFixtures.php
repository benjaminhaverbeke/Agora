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


class GeneralFixtures extends Fixture
{


    public function __construct(
        private readonly UserPasswordHasherInterface $hasher, UserRepository $um
    )
    {

    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setEmail('admin@gmail.com')
            ->setUsername('Admin')
            ->setPassword($this->hasher->hashPassword($user, 'admin'));
        $user2 = new User();
        $user2->setRoles(['ROLE_USER'])
            ->setEmail('benjamin@gmail.com')
            ->setUsername('Benjamin')
            ->setPassword($this->hasher->hashPassword($user2, 'test'));
        $user3 = new User();
        $user3->setRoles(['ROLE_USER'])
            ->setEmail('lea@gmail.com')
            ->setUsername('Léa')
            ->setPassword($this->hasher->hashPassword($user3, 'test'));
        $user4 = new User();
        $user4->setRoles(['ROLE_USER'])
            ->setEmail('carmen@gmail.com')
            ->setUsername('Carmen')
            ->setPassword($this->hasher->hashPassword($user4, 'test'));
        $user5 = new User();
        $user5->setRoles(['ROLE_USER'])
            ->setEmail('mathilde@gmail.com')
            ->setUsername('Mathilde')
            ->setPassword($this->hasher->hashPassword($user5, 'test'));
        $user6 = new User();
        $user6->setRoles(['ROLE_USER'])
            ->setEmail('bob@gmail.com')
            ->setUsername('Bob')
            ->setPassword($this->hasher->hashPassword($user6, 'test'));

        $userArray = [$user, $user2, $user3, $user4, $user5, $user6];
        $mentions = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];

        for($i = 0; $i < 5; $i++ )
        {
            $keySalon = array_rand($userArray, 1);
            $campagne = new \DateTimeImmutable('2024-10-12 10:00:00');
            $vote = new \DateTimeImmutable('2024-10-15 15:00:00');

            $titleSalon = ($i+1).' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices accumsan enim vitae interdum';

            $contentSalon = ($i+1).' Aenean fermentum tortor imperdiet elit rhoncus tempor. Etiam maximus commodo libero, 
                nec lobortis massa auctor non. Pellentesque eu turpis id lorem facilisis malesuada. 
                Etiam gravida vulputate efficitur. Vestibulum vel ullamcorper velit. 
                Integer nunc magna, auctor vitae semper sed, vulputate sit amet nisl. 
                Proin in neque vitae turpis vulputate ultrices ut at purus. 
                Duis vel purus elementum quam iaculis aliquam id ac lectus. 
                Donec fringilla suscipit aliquam. Donec et erat eget lectus sagittis finibus nec et nunc. 
                Vestibulum non posuere ipsum, semper condimentum leo.';

            $salon = new Salon();
            $salon
                ->setTitle($titleSalon)
                ->setDescription($contentSalon)
                ->setUser($userArray[$keySalon])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setDateCampagne($campagne)
                ->setDateVote($vote)
                ->addUser($user)
                ->addUser($user2)
                ->addUser($user3)
                ->addUser($user4)
                ->addUser($user5)
                ->addUser($user6);

            $user->addSalon($salon);
            $user2->addSalon($salon);
            $user3->addSalon($salon);
            $user4->addSalon($salon);
            $user5->addSalon($salon);
            $user6->addSalon($salon);

            for($n = 0; $n < 6; $n++ ){
                $keySujet = array_rand($userArray, 1);
                $contentSujet = ($n+1).' Aenean congue nisl eget aliquet tempus. Maecenas in massa dolor. Mauris vitae pulvinar nisl. Nulla facilisi. Nam maximus interdum pellentesque. Phasellus id blandit lacus. Sed malesuada augue nisl, malesuada bibendum nisl vestibulum et. Mauris ullamcorper consectetur feugiat. Duis sit amet arcu ut velit cursus ultricies. Nam molestie augue sed tellus feugiat aliquet. Duis volutpat elementum ligula, sit amet viverra nisl bibendum vitae. Integer eu lacus aliquet, interdum elit vitae, rutrum nisi.';
                $titleSujet = ($n+1).' Lorem ipsum dolor sit amet';


                $sujet = new Sujet();
                $sujet->setTitle($titleSujet)
                    ->setDescription($contentSujet)
                    ->setSalon($salon)
                    ->setUser($userArray[$keySujet]);


                for($o = 0; $o < 6; $o++ ){
                    $keyProposal = array_rand($userArray, 1);
                    $contentProposal = ($o+1).' Sed vel ultrices ligula, id hendrerit mauris. Curabitur facilisis, mauris eget accumsan gravida, diam urna ullamcorper felis, eget vehicula dui risus ut risus. Sed mattis, ante ut vestibulum convallis, ipsum purus ultricies magna, sit amet lacinia risus tortor sed ex. Morbi velit nisl, facilisis porttitor sapien vel, dapibus egestas justo. Cras non arcu semper, commodo nunc vitae, imperdiet est. Nullam interdum vel turpis quis auctor. Vivamus ipsum quam, scelerisque ac ipsum at, facilisis sagittis lacus.';
                    $titleProposal = ($o+1).' Lorem ipsum dolor sit amet';

                    $proposal = new Proposal();
                    $proposal->setTitle($titleProposal)
                        ->setDescription($contentProposal)
                        ->setSalon($salon)
                        ->setUser($userArray[$keyProposal])
                        ->setSujet($sujet);

                    foreach($userArray as $voter){

                        $voteKey = array_rand($mentions);
                        $vote = new Vote();

                        $vote->setSujet($sujet)
                            ->setNotes($mentions[$voteKey])
                            ->setUser($voter)
                            ->setProposal($proposal);

                        $sujet->addVoter($voter);
                        $proposal->addVote($vote);

                        $voter->addVoted($sujet);

                    }

                    $sujet->addProposal($proposal);
                }
                $salon->addSujet($sujet);

            }
            $manager->persist($salon);
            $manager->flush();
        }
















    }
}
