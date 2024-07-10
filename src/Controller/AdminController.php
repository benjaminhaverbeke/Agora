<?php

namespace App\Controller;

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
}
