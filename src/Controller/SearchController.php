<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search/{slug}', name: 'search', requirements: ['slug' => '[a-z0-9-]+'])]
    public function index(Request $request, string $slug): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'slug' => $slug
        ]);
    }
}
