<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RelatedProducts;
use App\Entity\Product;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        $productNewArrival = $productRepository->findByIsNewArrival(1);

        $productSpecialOffer = $productRepository->findByIsSpecialOffer(1);
        //dd([$productNewArrival, $productSpecialOffer]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productNewArrival' => $productNewArrival,
            'productSpecialOffer' => $productSpecialOffer,
        ]);
    }

    //voir le detail d'un produit
    #[Route('/product/{slug}', name: 'product_details')]
    public function show(?Product $product): Response
    {
        if(!$product){
            return $this->redirectToRoute("home");
        }
        return $this->render("home/single_product.html.twig",[
            'product' => $product
        ]);
    }
}
