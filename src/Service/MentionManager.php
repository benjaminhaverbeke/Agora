<?php

namespace App\Service;
use App\Entity\Votes;
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


//        $mentions = [
//            "inadapte" => 0,
//            "passable" => 0,
//            "bien" => 0,
//            "tresbien" => 0,
//            "excellent" => 0
//        ];

        /********comparer tableau nbmentions et tableau notes ********/

        /***incremeter valeurs mentions avec les notes correspondantes trouvées*********/
        /***TROUVER LE MOYEN D'automatiser sans changer dans un premier temps les parametres de la méthode*******/
        /***fariquer tableau deux dimensions ??***/

        /*
         * $mentionsUsed = ['inadapte, 'passsable', 'bien', 'tresbien'];
         * $notes = ['tresbien', 'bien', 'bien', bien', 'passable']
         *
         *
         * $notesCount = [$mentionsUsed, $notes];
         *foreach($notesCount as $sous_notes){
         *
         *              foreach($sous_notes[0] as $mentions){
         *
         *              $mentions
         * }
         *
         * }
         */


//        foreach ($arraynotes as $note) {
//
//            switch ($note) {
//                case "excellent":
//                    $mentions['excellent']++;
//                    break;
//                case "tresbien":
//                    $mentions['tresbien']++;
//                    break;
//                case "bien":
//                    $mentions['bien']++;
//                    break;
//                case "passable":
//                    $mentions['passable']++;
//                    break;
//                case "inadapte":
//                    $mentions['inadapte']++;
//                    break;
//
//            }
//
//
//        }
            /********calcul cumul effectif ******/
        /**adapter le calcul du cumul au nb de mentions****/



//        $inadaptecumul = $mentions['inadapte'];
//        $passablecumul = $mentions['passable'] + $inadaptecumul;
//        $biencumul = $mentions['bien'] + $passablecumul;
//        $tresbiencumul = $mentions['tresbien'] + $biencumul;
//        $excellentcumul = $mentions['excellent'] + $tresbiencumul;
//
//
//        /****total toujours bon*******c'est pour le pourcent ****/
//        $total = count($arraynotes);
//
//        /******a revoir ? a la fin***/
//        $pourcent = [
//            "inadapte" => ($mentions['inadapte'] > 0) ? (int)(($mentions['inadapte'] * 100) / $total) : 0,
//            "passable" => ($mentions['passable'] > 0) ? (int)(($mentions['passable'] * 100) / $total) :0,
//            "bien" => ($mentions['bien'] > 0) ? (int)(($mentions['bien'] * 100) / $total) : 0,
//            "tresbien" => ($mentions['tresbien'] > 0) ? (int)(($mentions['tresbien'] * 100) / $total) : 0,
//            "excellent" => ($mentions['excellent'] > 0) ? (int)(($mentions['excellent'] * 100) / $total) : 0
//        ];


//        $mediane = $excellentcumul / 2;
//
//        $mentionMajoritaire = "bien";
//
///*********a revoir avec de nouvelle valeur pour cumul***************/
//
//        if ($mediane <= $inadaptecumul) {
//            $mentionMajoritaire = "inadapté";
//        } elseif ($mediane <= $passablecumul) {
//            $mentionMajoritaire = "passable";
//        } elseif ($mediane <= $biencumul) {
//            $mentionMajoritaire = "bien";
//        } elseif ($mediane <= $tresbiencumul) {
//            $mentionMajoritaire = "tresbien";
//        } else {
//            $mentionMajoritaire = "excellent";
////        }
//
//        $elu = new MentionManager();
//        $elu->setMentionMajoritaire($mentionMajoritaire);
//        $elu->setPourcent($pourcent);
//        $elu->setMediane($mediane);


    }


}