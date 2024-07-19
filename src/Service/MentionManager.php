<?php

namespace App\Service;
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
            "inadapte" => (int)(($mentions['inadapte'] * 100) / $total),
            "passable" => (int)(($mentions['passable'] * 100) / $total),
            "bien" => (int)(($mentions['bien'] * 100) / $total),
            "tresbien" => (int)(($mentions['tresbien'] * 100) / $total),
            "excellent" => (int)(($mentions['excellent'] * 100) / $total)
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