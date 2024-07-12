<?php

namespace App\Controller;

use App\Repository\SalonsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(SalonsRepository $repository): Response
    {

        $salons = $repository->findAll();

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'salons' => $salons,
        ]);
    }
}
