<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProposalController extends AbstractController
{
    #[Route('/proposal/{id}', name: 'proposal.index', requirements: ['id' => '\d+'])]
    public function index(): Response
    {
        return $this->render('proposal/index.html.twig', [
            'controller_name' => 'ProposalController',
        ]);
    }
}
