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
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

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
        MenuItem::linkToRoute('Retour sur le site', 'fas fa-right-left', 'home'),
        MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
        MenuItem::linkToCrud('Message', 'fas fa-message', Message::class),
        MenuItem::linkToCrud('Contact', 'fas fa-address-book', Contact::class),
        MenuItem::linkToCrud('Sujet', 'fas fa-list-check', Sujet::class),
        MenuItem::linkToCrud('Proposal', 'fas fa-pen', Proposal::class),
        MenuItem::linkToCrud('Vote', 'fas fa-check-to-slot', Vote::class),
        MenuItem::linkToCrud('Salon', 'fas fa-users', Salon::class),
        MenuItem::linkToCrud('Invitation', 'fas fa-envelope', Invitation::class),
        ];


    }


}
