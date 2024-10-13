<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SiteMapController extends AbstractController
{
    #[Route('/site_map', name: 'site_map')]
    public function index(): Response
    {
        return $this->render('site_map/index.html.twig');
    }
}
