<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\Contact;
use App\Entity\Invitation;
use App\Entity\Salon;
use App\Entity\Sujet;
use App\Entity\Vote;
use App\Entity\Proposal;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        if($this->isGranted())
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Agora');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour sur le site', 'fas fa-list', 'home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Message', 'fas fa-list', Message::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class);
        yield MenuItem::linkToCrud('Sujet', 'fas fa-list', Sujet::class);
        yield MenuItem::linkToCrud('Proposal', 'fas fa-list', Proposal::class);
        yield MenuItem::linkToCrud('Vote', 'fas fa-list', Vote::class);
        yield MenuItem::linkToCrud('Salon', 'fas fa-list', Salon::class);
        yield MenuItem::linkToCrud('Invitation', 'fas fa-list', Invitation::class);

    }
}
