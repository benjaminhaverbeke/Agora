<?php


namespace App\Repository;
class ElectionManager
{

    private string $victoire;

    public function IsElected(array $proposalsArray)
    {


    }


    public function getVictoire(): string
    {
        return $this->victoire;
    }

    public function setVictoire(string $victoire): void
    {
        $this->victoire = $victoire;
    }


}