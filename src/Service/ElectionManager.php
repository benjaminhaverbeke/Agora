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
        $mentions_depart = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];


        $resultprop = [];
        foreach ($props as $prop) {


            $votes = $this->vm->findAllVotesByProposal($prop->getId());


            if (count($votes) > 0) {


                $notearray = [];


                foreach ($votes as $vote) {


                    $notearray[] = $vote->getNotes();


                }

                $result = $this->mm->calculMention($notearray, $mentions_depart);

                $resultprop[] = [
                    "proposalId" => $prop->getId(),
                    "mention_win" => $result->getMentionGagnante(),
                    "pourcent" => $result->getPourcent()

                ];


            }


        }

        return $resultprop;

    }

    public function allWinnerMentions(array $mentionsarray): array
    {


        /****itération de la mention la plus grande à la plus petite***/

        $comparearray = ["inadapte", "passable", "bien", "tresbien", "excellent"];
        /***stock le ou les résultats***/
        $matchingVotes = [];
        for ($i = count($comparearray) - 1; $i >= 0; $i--) {
            /**stocke la plus grande mention a chaque iteration***/
            $currentMentionWin = $comparearray[$i];


            foreach ($mentionsarray as $mention) {
                /***compare la mention du tableau en input a la plus grande mention stockee***/
                /***il peut donc y en avoir plusieurs***/
                if ($mention['mention_win'] === $currentMentionWin) {


                    $matchingVotes[] = $mention;


                }

            }

            /***sortie du foreach sur le tableau en input, on regarde si le tableau matchingVotes possede au moins une valeur, si oui on break***/

            if (!empty($matchingVotes)) {


                break;

            }


        }

        return $matchingVotes;

    }


    public function departageMentions(array $matchingVotes)
    {
        /***mentions de départ***/
        $mentions = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];

        /***on stock le match dans un tableau qui sera vérifié chaque tour, si sa valeur est unique alors il y a un candidat gagnant***/

        $lastresult = $matchingVotes;

        /***la mention gagnante ou a égalité en entrée ***/
        $winmention = $lastresult[0]['mention_win'];

        /***tableau a remplir avec les mention exclue qui a precedemment gagne****/
        $mentionsExcluded = [];

        /***seulement 3 tours possibles***/

        for ($i = 0; $i < 3; $i++) {


            if (count($lastresult) === 1) {

                /***si une seule valeur dans matchingvotes alors le candidat a gagne ***/

                return $lastresult[0];

            } else {

                /***si c'est 2eme tour ou 3eme alors on stock la mention gagnante précédente dans winmention pour pouvoir l'exclure du vote***/
                $winmention = $lastresult[0]["mention_win"];

                /***on rajoute la derniere mention gagnante au tableau pour l'exclure de la query SQL ***/
                $mentionsExcluded[] = $winmention;

                $newresult = [];

                foreach ($lastresult as $match) {

                    /****on trouve les objets votes qui sont liés à la proposition, et on exclue les mentions qui ont déjà gagné***/

                    $newvotes = $this->vm->findNotesWithoutSpecificMention($match['proposalId'], $mentionsExcluded);


                    $notesarray = [];

                    foreach ($newvotes as $vote) {

                        $notesarray[] = $vote->getNotes();


                    }


                    $result = $this->mm->calculMention($notesarray, $mentions);
//                            var_dump($result);


                    $newresult[] =
                        [
                            "proposalId" => $vote->getProposal()->getId(),
                            "mention_win" => $result->getMentionGagnante(),
                            "pourcent" => $result->getPourcent()

                        ];


                }


                $lastresult = $this->allWinnerMentions($newresult);

                if (count($lastresult) === 1) {

                    echo $lastresult[0]["mention_win"];
                    return $lastresult;
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



