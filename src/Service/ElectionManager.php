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

        $voteArray =[];

        foreach ($allprops as $prop) {


            $votes = $this->vm->findAllVotesByProposal($prop->getId());

            $votearray = [];

            foreach($votes as $vote) {

                   $votearray[] = $vote->getNotes();
            }


                $result = $this->mm->calculMention($votearray);



            $votearray = [
                "proposalId" => $prop->getId(),
                "note" => $votearray,
                "mention_win" => $result->getMentionMajoritaire()
            ];

            var_dump($votearray);
        }







    }
}


