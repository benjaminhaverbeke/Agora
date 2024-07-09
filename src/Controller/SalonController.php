<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SalonController extends AbstractController
{
    #[Route('/salon/{slug}-{id}', name: 'salon', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function index(Request $request, string $slug, int $id): Response
    {


        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            "slug" => $slug,
            "id" => $id,


        ]);
    }
}
