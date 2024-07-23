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

        $resultprop = [];
        foreach ($allprops as $prop) {


            $votes = $this->vm->findAllVotesByProposal($prop->getId());


            if (count($votes) > 0) {


                $notearray = [];

                foreach ($votes as $vote) {


                    $notearray[] = $vote->getNotes();


                }
                $result = $this->mm->calculMention($notearray);

                $resultprop[] = [
                    "proposalId" => $prop->getId(),
                    "mention_win" => $result->getMentionMajoritaire(),
                    "pourcent" => $result->getPourcent()

                ];


            }

        }


        $comparearray = ["inadapte", "passable", "bien", "tresbien", "excellent"];

        $matchingVotes = [];
        for ($i = count($comparearray) - 1; $i >= 0; $i--) {
            $currentMentionWin = $comparearray[$i];


            foreach ($resultprop as $value) {

                if ($value['mention_win'] === $currentMentionWin) {


                    $matchingVotes[] = $value;


                }

            }

            if (!empty($matchingVotes)) {

                break;

            }


        }

        /****Une seule mention gagnante = mention majoritaire*********/

        if (count($matchingVotes) === 1) {


            return $matchingVotes[0];
        } /*****sinon comparaison entre mentions par pourcentages********/
        else {
            $winmention = $matchingVotes[0]['mention_win'];

            foreach ($matchingVotes as $index => $vote) {

                $key = array_search($winmention, $comparearray);


                $superior = array_splice($vote['pourcent'], $key + 1);
                $inferior = array_splice($vote['pourcent'], 0, $key);


                $opposition = array_sum($inferior);
                $partisan = array_sum($superior);


                $matchingVotes[$index]["opposition"] = $opposition;
                $matchingVotes[$index]["partisan"] = $partisan;


            }

////            if(count($opposition) > 1)
////            {
////
////                array_intersect_ukey()
////
////
////            }
//
//
//                $keyBigPart = array_search(max($partisan), $partisan);


g
            return $matchingVotes[$keyBigPart];


        }

    }
}


