<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);         //methode pour ranger les commandes par ordre décroissant
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('user.FullName' , 'Client'),
            TextField::new('carrierName' , 'Carrier Name'),
            MoneyField::new('carrierPrice' , 'Frais de livraison')->setCurrency('EUR'),
            MoneyField::new('subTotalHT' , 'Subtotal HT')->setCurrency('EUR'),
            MoneyField::new('taxe' , 'TVA')->setCurrency('EUR'),
            MoneyField::new('subTotalTTC' , 'Subtotal TTC')->setCurrency('EUR'),
            BooleanField::new('isPaid'),
        ];
    }
    
}
