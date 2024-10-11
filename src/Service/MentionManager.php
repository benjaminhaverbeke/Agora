<?php

namespace App\Service;
use App\Entity\Vote;
class MentionManager
{


    private ?float $mediane;

    private ?array $pourcent;

    private ?string $mentionGagnante;


    public function __construct()
    {


    }


    public function getMediane(): ?float
    {
        return $this->mediane;
    }

    public function setMediane(?float $mediane): void
    {
        $this->mediane = $mediane;
    }

    public function getMentionGagnante(): ?string
    {
        return $this->mentionGagnante;
    }

    public function setMentionGagnante(?string $mentionGagnante): void
    {
        $this->mentionGagnante = $mentionGagnante;
    }

    public function getPourcent(): ?array
    {
        return $this->pourcent;
    }

    public function setPourcent(?array $pourcent): void
    {
        $this->pourcent = $pourcent;
    }

    public function calculMention(array $notesinput, array $mentions): MentionManager
    {

       /**take notes array and a mention array in parameters (mentions can vary)**/

        $notesoutput = [
            "inadapte" => 0,
            "passable" => 0,
            "bien" => 0,
            "tresbien" => 0,
            "excellent" => 0
        ];

        /**this iteration take input and check if it matches with specific mention, positive results are stocked into notesoutput***/

        foreach ($mentions as $mention) {

            foreach($notesinput as $note){

                if($mention === $note){

                    $notesoutput[$mention] = $notesoutput[$mention]+1;


                }

            }
        }



/***this part's getting cumulative workforce (effectifs cumulés)***/

        $effectifsCumules = [];
        $effectifTotal = 0;


        foreach ($notesoutput as $note => $effectif) {
            $effectifTotal += $effectif;
            $effectifsCumules[$note] = $effectifTotal;

        }

/***percent calculation***/

        $pourcentages = [];
        foreach ($notesoutput as $note => $effectif) {
            $pourcentages[$note] = $effectifTotal === 0 ? 0 : round(($effectif / $effectifTotal) * 100, 2);
        }


        $this->setPourcent($pourcentages);

        $mediane = $effectifTotal / 2;

        $this->setMediane($mediane);

        $effectifbas = 0;
        $mention_win = null;

        foreach ($effectifsCumules as $note => $effectif) {


            if ($mediane <= $effectif && $mediane > $effectifbas) {

                $mention_win = $note;
                break;
            } else {

                $effectifbas = $effectif;

            }

        }

        $this->setMentionGagnante($mention_win);


        return $this;


    }


}