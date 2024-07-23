<?php


namespace App\Service;

use App\Repository\ProposalsRepository;
use App\Entity\Sujets;
use App\Repository\VotesRepository;
use App\Service\MentionManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ElectionManager
{


    public function __construct(readonly ProposalsRepository $pm, readonly VotesRepository $vm, readonly MentionManager $mm)
    {


    }

    public function IsElected(int $id)
    {


        $allprops = $this->pm->AllPropositionSujet($id);

        $votearray = [];

        foreach ($allprops as $prop) {


            $votes = $this->vm->findAllVotesByProposal($prop->getId());


            if (count($votes) > 0) {

                $resultprop = [];
                $notearray = [];

                foreach ($votes as $vote) {


                    $notearray[] = $vote->getNotes();


                }
                $result = $this->mm->calculMention($notearray);

                $resultprop[] = [
                    "proposalId" => $prop->getId(),
                    "mention_win" => $result->getMentionMajoritaire()
                ];

                $votearray[] = $resultprop;

            }

        }


        $comparearray = ["inadapte", "passable", "bien", "tresbien", "excellent"];

        $matchingVotes = [];
        for ($i = count($comparearray) - 1; $i >= 0; $i--) {
            $currentMentionWin = $comparearray[$i];



            foreach ($votearray as $value) {

                if ($value[0]['mention_win'] === $currentMentionWin) {

                    $matchingVotes[] = $value;

                }





            }


        }
        var_dump($matchingVotes);

    }
}


