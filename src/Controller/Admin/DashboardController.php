<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Categories;
use App\Entity\Product;
use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\Cart;
use App\Entity\HomeSlider;
use App\Entity\Contact;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(OrderCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tricot En Duo');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home', 'homepage');
        yield MenuItem::linkToCrud('Product', 'fas fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Order', 'fas fa-shopping-bag', Order::class);
        yield MenuItem::linkToCrud('Cart', 'fas fa-boxes', Cart::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Categories::class);
        yield MenuItem::linkToCrud('Carrier', 'fas fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Home Slider', 'fas fa-images', HomeSlider::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-envelope', Contact::class);
    }
}
