<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalonsRepository;
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(Request $request, SalonsRepository $repository): Response
    {

        $salons = $repository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'salons' => $salons
        ]);
    }

    #[Route('/admin/users', name: 'admin.users')]
    public function users(Request $request, UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);
    }
}
