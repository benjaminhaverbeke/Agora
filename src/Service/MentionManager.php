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
        /***** parametres nb de mention variable, notes******/

        $notesoutput = [];

        foreach ($mentions as $mention) {

            foreach($notesinput as $note){

                $value = null;

                if($mention === $note  && isset($notesoutput[$mention])){

                    $value = $notesoutput[$mention];
                    $notesoutput[$mention] = $value+1;
                }



                if(empty($notesoutput[$mention])){

                    $notesoutput[$mention] = 0;
                }

            }
        }

/***notesoutput est un tableau associatif qui recupere les mentions utilisées et comptabilise les points par mention***/


/****Calcul des effectifs cumulés*****/

        $effectifsCumules = [];
        $effectifTotal = 0;


        foreach ($notesoutput as $note => $effectif) {
            $effectifTotal += $effectif;
            $effectifsCumules[$note] = $effectifTotal;

        }

/***Calcul des pourcentages****/
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