<?php

namespace App\Service;
use App\Entity\Votes;
class MentionManager
{


    private ?float $mediane;

    private ?array $pourcent;

    private ?string $mentionMajoritaire;


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

    public function getMentionMajoritaire(): ?string
    {
        return $this->mentionMajoritaire;
    }

    public function setMentionMajoritaire(?string $mentionMajoritaire): void
    {
        $this->mentionMajoritaire = $mentionMajoritaire;
    }

    public function getPourcent(): ?array
    {
        return $this->pourcent;
    }

    public function setPourcent(?array $pourcent): void
    {
        $this->pourcent = $pourcent;
    }

    public function calculMention(array $arraynotes): MentionManager
    {

        $mentions = [
            "inadapte" => 0,
            "passable" => 0,
            "bien" => 0,
            "tresbien" => 0,
            "excellent" => 0
        ];


        foreach ($arraynotes as $note) {

            switch ($note) {
                case "E":
                    $mentions['excellent']++;
                    break;
                case "TB":
                    $mentions['tresbien']++;
                    break;
                case "B":
                    $mentions['bien']++;
                    break;
                case "P":
                    $mentions['passable']++;
                    break;
                case "I":
                    $mentions['inadapte']++;
                    break;

            }


        }

        $inadaptecumul = $mentions['inadapte'];
        $passablecumul = $mentions['passable'] + $inadaptecumul;
        $biencumul = $mentions['bien'] + $passablecumul;
        $tresbiencumul = $mentions['tresbien'] + $biencumul;
        $excellentcumul = $mentions['excellent'] + $tresbiencumul;

        $total = count($arraynotes);


        $pourcent = [
            "inadapte" => ($mentions['inadapte'] > 0) ? (int)(($mentions['inadapte'] * 100) / $total) : 0,
            "passable" => ($mentions['passable'] > 0) ? (int)(($mentions['passable'] * 100) / $total) :0,
            "bien" => ($mentions['bien'] > 0) ? (int)(($mentions['bien'] * 100) / $total) : 0,
            "tresbien" => ($mentions['tresbien'] > 0) ? (int)(($mentions['tresbien'] * 100) / $total) : 0,
            "excellent" => ($mentions['excellent'] > 0) ? (int)(($mentions['excellent'] * 100) / $total) : 0
        ];


        $mediane = $excellentcumul / 2;

        $mentionMajoritaire = "bien";


        if ($mediane <= $inadaptecumul) {
            $mentionMajoritaire = "inadapté";
        } elseif ($mediane <= $passablecumul) {
            $mentionMajoritaire = "passable";
        } elseif ($mediane <= $biencumul) {
            $mentionMajoritaire = "bien";
        } elseif ($mediane <= $tresbiencumul) {
            $mentionMajoritaire = "tresbien";
        } else {
            $mentionMajoritaire = "excellent";
        }

        $elu = new MentionManager();
        $elu->setMentionMajoritaire($mentionMajoritaire);
        $elu->setPourcent($pourcent);
        $elu->setMediane($mediane);

        return $elu;

    }


}