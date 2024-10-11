<?php


namespace App\Service;

use App\Repository\ProposalRepository;
use App\Entity\Sujet;
use App\Repository\VoteRepository;
use App\Service\MentionManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

readonly class ElectionManager
{


    public function __construct(readonly ProposalRepository $pm, readonly VoteRepository $vm, readonly MentionManager $mm)
    {


    }

    /***get all winner mentions by proposal***/
    public function allMentionsByProposal(array $props): array
    {
        /***input asks proposals array***/

        /***basic mentions used, could be more complex***/

        $mentions = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];


        /***empty array needed for output function***/
        $resultprop = [];


        foreach ($props as $prop) {

            /***getting votes for each proposal***/
            $votes = $this->vm->findAllVotesByProposal($prop->getId());


            if (count($votes) > 0) {


                $notearray = [];


                foreach ($votes as $vote) {

                    /***notes filling array***/

                    $notearray[] = $vote->getNotes();


                }

                /***get winner mention for this proposal***/

                $result = $this->mm->calculMention($notearray, $mentions);

                /***some necessary informations stocked in a new array***/

                $resultprop[] = [
                    "proposalId" => (string)$prop->getId(),
                    "proposalTitle" => $prop->getTitle(),
                    "proposalDescription" => $prop->getDescription(),
                    "mention_win" => $result->getMentionGagnante(),
                    "mediane" => $result->getMediane(),
                    "pourcent" => $result->getPourcent()

                ];


            }


        }


        return $resultprop;

    }


    /***this method is a first filter to check winners***/
    public function allWinnerMentions(array $mentionsarray): array
    {

        /***iteration order by desc, because we are looking for the higher mention***/
        $comparearray = ["inadapte", "passable", "bien", "tresbien", "excellent"];

        /***could be some equality***/
        $matchingVotes = [];

        for ($i = count($comparearray) - 1; $i >= 0; $i--) {

            $currentMentionWin = $comparearray[$i];


            foreach ($mentionsarray as $mention) {

                if ($mention['mention_win'] === $currentMentionWin) {


                    $matchingVotes[] = $mention;


                }

            }

            /***this iteration stops at the end of a mention, one or more values could be stored in the array***/

            if (!empty($matchingVotes)) {


                break;

            }


        }


        return $matchingVotes;

    }

    /***this method aims to differentiate between proposals in cas multiple results remain***/
    public function departageMentions(array $matchingVotes): array
    {

        $mentions = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent'];


        /***stocking match in an array that will be checked each round, if its value is unique, associated proposal is the winning one***/

        $lastresult = $matchingVotes;

        /***need to also initialize an array to exclud precedent winner mentions***/

        $mentionsExcluded = [];

        /****rounds are stocked***/
        $stocklastresult = [];

        for ($i = 0; $i < 2; $i++) {


            $stocklastresult[] = $lastresult;


            if (count($lastresult) === 1) {


                return $stocklastresult;

            } else {

                /***if more than one result, we stock actual mention to be excluded on the next iteration***/

                if (isset($lastresult[0]["mention_win"])) {

                    $winmention = $lastresult[0]["mention_win"];
                } else {

                    return $stocklastresult;

                }

                $mentionsExcluded[] = $winmention;

                $newresult = [];

                foreach ($lastresult as $match) {

                    /***Once the mention is excluded, a new query must be sent to the database to find votes that do not contain the excluded mention(s)***/

                    $newvotes = $this->vm->findNotesWithoutSpecificMention($match['proposalId'], $mentionsExcluded);


                    $notesarray = [];

                    foreach ($newvotes as $vote) {


                        $notesarray[] = $vote->getNotes();

                    }


                    /***We compare the base mentions with the excluded mentions. A new table is outputted without the excluded mentions,
                     so that we can recalculate the winning mention***/

                    $new_mentions = array_diff($mentions, $mentionsExcluded);

                    $result = $this->mm->calculMention($notesarray, $new_mentions);

                    $newresult[] =
                        [
                            "proposalId" => (string)$match['proposalId'],
                            "proposalTitle" => $match['proposalTitle'],
                            "proposalDescription" => $match['proposalDescription'],
                            "mention_win" => $result->getMentionGagnante(),
                            "mediane" => $result->getMediane(),
                            "pourcent" => $result->getPourcent()

                        ];


                }


                $lastresult = $this->allWinnerMentions($newresult);

            }


        }


        return $stocklastresult;

    }


    /***this last method taking a Sujet id in parameter ***/
    public function isElected(int $id): array
    {


        $all_props = $this->pm->allPropositionSujet($id);

        $all_mentions = $this->allMentionsByProposal($all_props);

        $all_win_mentions = $this->allWinnerMentions($all_mentions);

        $result = $this->departageMentions($all_win_mentions);

        $results = [$all_mentions];

        foreach ($result as $match) {

            if (!empty($match)) {

                $results[] = $match;

            }

        }


        return $results;

    }


}
