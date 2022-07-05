<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name')->hideOnIndex(),
            TextEditorField::new('description'),
            TextEditorField::new('moreInformations')->hideOnIndex(),
            MoneyField::new('price')->setCurrency('USD'),
            IntegerField::new('quantity'),
            TextFIeld::new('tags'),
            BooleanField::new('isNewArrival', 'new arrival'),
            BooleanField::new('isSpecialOffer', 'special offer'),
            AssociationField::new('category'),
            ImageFIeld::new('picture1')->setBasePath('/assets/uploads/products/')
                                       ->setUploadDir('/public/assets/uploads/products/')
                                       ->setUploadedFileNamePattern('[randomhash].[extension]')
                                       ->setRequired(false),
            ImageFIeld::new('picture2')->setBasePath('/assets/uploads/products/')
                                       ->setUploadDir('/public/assets/uploads/products/')
                                       ->setUploadedFileNamePattern('[randomhash].[extension]')
                                       ->setRequired(false),
            ImageFIeld::new('picture3')->setBasePath('/assets/uploads/products/')
                                       ->setUploadDir('/public/assets/uploads/products/')
                                       ->setUploadedFileNamePattern('[randomhash].[extension]')
                                       ->setRequired(false),
        ];
    }
}
