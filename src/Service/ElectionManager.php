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

    $mentions = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];
        /***le but c'est de calculer les mentions gagnantes des ****/

        /***prend un tableau de Proposals *** en input ***/


        /****mentions de base utilisées ***/

        /***init tableau vide qui servira en sortie****/
        $resultprop = [];

        /***on traite chaque proposition****/

        foreach ($props as $prop) {

            /****fetch des tableau de Votes par Proposition****/
            $votes = $this->vm->findAllVotesByProposal($prop->getId());


            /****enleve prend pas les tableaux vides, !a ameliorer! ca doit pouvoir se faire au niveau de la requete sql****/

            if (count($votes) > 0) {


                $notearray = [];


                foreach ($votes as $vote) {

                    /***remplit le tableau de notes avec les notes des votes par proposition***/

                    $notearray[] = $vote->getNotes();


                }

                /***calcule la mention gagnante ****/

                $result = $this->mm->calculMention($notearray, $mentions);

                /****stocke la mention gagnante de LA proposition****/
                $resultprop[] = [
                    "proposalId" => $prop->getId(),
                    "mention_win" => $result->getMentionGagnante(),
                    "pourcent" => $result->getPourcent()

                ];


            }


        }

        /****retourne un tableau avec les mentions gagnantes de chaque proposition soumise****/


        return $resultprop;

    }

    /****stock les mentions gagnantes pour chaque proposition****/
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


        /***tableau a remplir avec les mention exclue qui a precedemment gagne****/
        $mentionsExcluded = [];

        /***seulement 3 tours possibles***/

        for ($i = 0; $i < 3; $i++) {

            /***on commence par reparder combien de valeurs dans le tableau lastresult****/

            if (count($lastresult) === 1) {

                /***si une seule valeur alors le candidat a gagne ***/

                return $lastresult[0];

            } else {

                /***si c'est 2eme tour ou 3eme alors on stock la mention gagnante précédente dans winmention pour pouvoir l'exclure du vote***/
                $winmention = $lastresult[0]["mention_win"];

                /***on rajoute la derniere mention gagnante au tableau pour l'exclure de la query SQL ***/
                $mentionsExcluded[] = $winmention;
//                var_dump($mentionsExcluded);
                $newresult = [];

                foreach ($lastresult as $match) {

                    /****on trouve les objets votes qui sont liés à la proposition, et on exclue les mentions qui ont déjà gagné***/

                    $newvotes = $this->vm->findNotesWithoutSpecificMention($match['proposalId'], $mentionsExcluded);

                    /***on init un tableau de note****par proposition****/
                    $notesarray = [];

                    foreach ($newvotes as $vote) {

                        /****stock les notes*****/
                        $notesarray[] = $vote->getNotes();

                    }
//                        var_dump($notesarray);
                    /***compare les mentions de base avec les mentions exclues* nouveau tableau sans les mentions exclues en sortie,
                     * pour pouvoir recalculer la mention gagnante***/

                    $new_mentions =  array_diff($mentions, $mentionsExcluded);



                    $result = $this->mm->calculMention($notesarray, $new_mentions);
//                   var_dump($result);


                    $newresult[] =
                        [
                            "proposalId" => $match['proposalId'],
                            "mention_win" => $result->getMentionGagnante(),
                            "pourcent" => $result->getPourcent()

                        ];


                }

                /****on compare et on stock la ou les meilleures mentions******/
                $lastresult = $this->allWinnerMentions($newresult);

            }

        }

        /****si on sort de la boucle (3tours max) alors les propositions sont à égalité,
         * dans ce cas on retourne un tableau avec plusieurs valeurs***
         */

        return $lastresult;

    }
}
