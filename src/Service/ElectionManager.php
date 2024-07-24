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

    public function allMentionsByProposal(array $props): array
    {

        $resultprop = [];
        foreach ($props as $prop) {


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

        return $resultprop;

    }

    public function allWinnerMentions(array $mentionsarray): array
    {


        $comparearray = ["inadapte", "passable", "bien", "tresbien", "excellent"];

        $matchingVotes = [];
        for ($i = count($comparearray) - 1; $i >= 0; $i--) {

            $currentMentionWin = $comparearray[$i];


            foreach ($mentionsarray as $mention) {


                if ($mention['mention_win'] === $currentMentionWin) {


                    $matchingVotes[] = $mention;


                }

            }

            if (!empty($matchingVotes)) {


                break;

            }


        }


        return $matchingVotes;



    }


    /****Une seule mention gagnante = mention majoritaire*********/

    public function departageMentions(array $matchingVotes)
    {


        $lastresult = [];
        $winmention = $matchingVotes[0]['mention_win'];
        $mentionsExcluded = [];


            if (count($matchingVotes) === 1) {

                /***si une seule valeur dans matchingvotes alors le candidat a gagne ***/

                return $matchingVotes[0];

            }
            else {


                /*******soit le premier choix est gagnant c'est a dire un seul resultat, soit ca itere, un tour deja fait, 4 tours maximum ***/
                for($i = 0 ; $i < 4 ; $i++){

                    /***tableau a remplir avec la mention exclue qui a precedemment gagne****/




                        if($i === 0){

                            $current_match = $matchingVotes;

                        }

                        else
                        {

                            $winmention = $lastresult[0]["mention_win"];
                            $current_match = $lastresult;

                        }


                        $mentionsExcluded[] = $winmention;

                        $newresult = [];

                        foreach ($current_match as $match) {


                            $newvotes = $this->vm->findNotesWithoutSpecificMention($match['proposalId'], $mentionsExcluded);


                            $notesarray = [];

                            foreach($newvotes as $vote){

                                $notesarray[]= $vote->getNotes();


                            }


                            $result = $this->mm->calculMention($notesarray);



                            $newresult[]=
                                [
                                    "proposalId" => $vote->getProposal()->getId(),
                                "mention_win" => $result->getMentionMajoritaire(),
                                "pourcent" => $result->getPourcent()

                            ];


                        }



                        $lastresult = $this->allWinnerMentions($newresult);
                        var_dump($lastresult);
                        if(count($lastresult) === 1){

                            echo $lastresult[0]["mention_win"];
                            return $lastresult;
                        }



                }

                echo "egalite";
                return $lastresult;


            }





//            $winmention = $matchingVotes[0]['mention_win'];
//
//            $notesstock = [];
//
//            $comparearray = ["inadapte", "passable", "bien", "tresbien", "excellent"];
//
//            $key = array_search($winmention, $comparearray);
//
//            $maxoppostock = [];
//            $maxpartstock = [];
//            $maxoppovalue = 0;
//            $maxpartvalue = 0;
//
//            foreach ($matchingVotes as $index => $vote) {
//
//
//                $superior = array_splice($vote['pourcent'], $key + 1);
//                $inferior = array_splice($vote['pourcent'], 0, $key);
//
//
//                $opposition = array_sum($inferior);
//                $partisan = array_sum($superior);
//
//                $matchingVotes[$index]["partisan_mention"] = $superior;
//                $matchingVotes[$index]["opposition_mention"] = $inferior;
//                $matchingVotes[$index]["partisan"] = $partisan;
//                $matchingVotes[$index]["opposition"] = $opposition;
//
//
//
//
//            }


        /* cherche la plus grande opposition et partisan pour chaque match **/


//            foreach ($matchingVotes as $match) {
//
//
//                if (isset($match["partisan"])) {
//
//                    $valeur_courante = $match["partisan"];
//
//                    if ($valeur_courante >= $valeur_max_part || is_null($valeur_max_part)) {
//
//                        $maxpartstock[] = $match;
//                        $valeur_max_part = $valeur_courante;
//
//                    }
//
//                    $valeur_courante = $match["opposition"];
//
//                    if ($valeur_courante >= $valeur_max_oppo || is_null($valeur_max_oppo)) {
//
//                        $maxoppostock[] = $match;
//                        $valeur_max_oppo = $valeur_courante;
//
//                    }
//
//
    }
//
//
}

/*regarde si il y a plus d'une valeur dans partisan, si une valeur ca retourne cette valeur, si plus d'une valeur, ca regarde la plus grande opposition*/


//
//
//            foreach ($matchingVotes as $index => $vote) {
//
//
//
//
//                $matchingVotes[$index]["opposition"] = $opposition;
//                $matchingVotes[$index]["partisan"] = $partisan;
//
//
//                if ($matchingVotes[$index]["opposition"] >= $maxoppovalue) {
//
//                    $maxoppostock[] = $matchingVotes[$index];
//
//                }
//
//                if ($matchingVotes[$index]["partisan"] >= $maxpartvalue) {
//
//                    $maxpartstock[] = $matchingVotes[$index];
//
//                }
//
//                if (count($maxpartstock) > 1) {
//
//
//
//                }


//                $keyBigPart = array_search(max($partisan), $partisan);


//            }



