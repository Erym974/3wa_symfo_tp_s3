<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Category;


class DashboardController extends AbstractDashboardController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        
        return $this->render('dashboard/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->getParameter('site_name'));
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Menu');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-arrow-left', 'home.index');
        yield MenuItem::section('Gestion des produits');
        yield MenuItem::LinkToCrud('Categories', 'fas fa-list', Category::class)->setPermission('ROLE_MANAGER');
        yield MenuItem::linkToCrud('Products', 'fas fa-list', Product::class)->setPermission('ROLE_MANAGER');
        yield MenuItem::section('Gestion des commandes')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Orders', 'fas fa-credit-card', Order::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::section('Gestion des utilisateurs')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::section('Gestion des médias');
        yield MenuItem::LinkToCrud('Media', 'fas fa-images', Media::class)->setPermission('ROLE_MANAGER');

        
    }
}
