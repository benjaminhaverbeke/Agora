<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractDashboardController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/bundle', name: 'bundle')]
    public function index(): Response
    {


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
//        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
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

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
        MenuItem::linkToRoute('Retour sur le site', 'fas fa-list', 'home'),
        MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class),
        MenuItem::linkToCrud('Message', 'fas fa-list', Message::class),
        MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class),
        MenuItem::linkToCrud('Sujet', 'fas fa-list', Sujet::class),
        MenuItem::linkToCrud('Proposal', 'fas fa-list', Proposal::class),
        MenuItem::linkToCrud('Vote', 'fas fa-list', Vote::class),
        MenuItem::linkToCrud('Salon', 'fas fa-list', Salon::class),
        MenuItem::linkToCrud('Invitation', 'fas fa-list', Invitation::class),
        ];


    }


}
