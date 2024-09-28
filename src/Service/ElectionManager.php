<?php


namespace App\Service;

use App\Repository\ProposalRepository;
use App\Entity\Sujet;
use App\Repository\VoteRepository;
use App\Service\MentionManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ElectionManager
{


    public function __construct(readonly ProposalRepository $pm, readonly VoteRepository $vm, readonly MentionManager $mm)
    {


    }

    public function allMentionsByProposal(array $props): array
    {

    $mentions = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];
        /***le but c'est de calculer les mentions gagnantes des ****/

        /***prend un tableau de Proposal *** en input ***/


        /****mentions de base utilisées ***/

        /***init tableau vide qui servira en sortie****/
        $resultprop = [];

        /***on traite chaque proposition****/


        foreach ($props as $prop) {

            /****fetch des tableau de Vote par Proposition****/
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
                    "proposalTitle" => $prop->getTitle(),
                    "proposalDescription" => $prop->getDescription(),
                    "mention_win" => $result->getMentionGagnante(),
                    "mediane" => $result->getMediane(),
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

        /****stock les tours********/
        $stocklastresult = [];
        /***seulement 4 tours possibles***/

        for ($i = 0; $i < 4; $i++) {

            $stocklastresult[] = $lastresult;

            /***on commence par reparder combien de valeurs dans le tableau lastresult****/

            if (count($lastresult) === 1) {

                /***si une seule valeur alors le candidat a gagne ***/
                /***retourne les differents tours****la derniere valeur est la gagnante****/

                return $stocklastresult;

            } else {

                /***si c'est 2eme tour ou 3eme alors on stock la mention gagnante précédente dans winmention pour pouvoir l'exclure du vote***/



                /***vérifie si il y a une mention gagnante*existe si iln'y en a pas alors il n'y avait qu'en mention et elle a déjà été stockée
                 * alors égalité**
                 */

                    if(isset($lastresult[0]["mention_win"])){

                        $winmention = $lastresult[0]["mention_win"];
                    }
                    else
                    {
                        /*** si aucune mention gagnante n'est stockée alors c'est que tous les votes
                         * ne portaient qu'une seule mention** pas de mention majoritaire**
                         * il y a donc égalité parfaite
                         * stockresult retourne les
                         */

//                        var_dump($stocklastresult[0]);
                        return $stocklastresult[0];

                    }


                /***on rajoute la derniere mention gagnante au tableau pour l'exclure de la query SQL ***/
                $mentionsExcluded[] = $winmention;
//             var_dump($mentionsExcluded);
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

//                    var_dump($new_mentions);

                    $result = $this->mm->calculMention($notesarray, $new_mentions);
//                  var_dump($result);

                        $newresult[] =
                            [
                                "proposalId" => $match['proposalId'],
                                "proposalTitle" => $match['proposalTitle'],
                                "proposalDescription" => $match['proposalDescription'],
                                "mention_win" => $result->getMentionGagnante(),
                                "mediane" => $result->getMediane(),
                                "pourcent" => $result->getPourcent()

                            ];





                }

                /****on compare et on stock la ou les meilleures mentions******/
                $lastresult = $this->allWinnerMentions($newresult);

            }



        }

        /****si on sort de la boucle (4tours max) alors les propositions sont à égalité,
         * dans ce cas on retourne un tableau avec plusieurs valeurs***
         */

        return $stocklastresult;

    }


    /*******prend un id de sujet en parametre*****/
    public function isElected(int $id) : array {

        /***retourne toutes les propositions pour un sujet donné***/
        $all_props = $this->pm->allPropositionSujet($id);


        /***retourne toutes les mentions gagnantes pour chaque proposition du sujet***/
        $all_mentions = $this->allMentionsByProposal($all_props);
//        var_dump($all_mentions);
        /***retourne la ou les propositions arrivées égalités avec la même mention*****/

        $all_win_mentions = $this->allWinnerMentions($all_mentions);
        //var_dump($all_win_mentions);

        /***retourne la ou les propositions arrivées en tête après départage***/
        $result = $this->departageMentions($all_win_mentions);
//       var_dump($result);

        return $result;

    }


}
