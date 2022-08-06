<?php

namespace App\Controller;

use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RelatedProducts;
use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository,
    HomeSliderRepository $homeSliderRepository): Response
    {
        $products = $productRepository->findAll();

        $homeSlider = $homeSliderRepository->findBy(['isDisplayed'=>true]);
        //dd($homeSlider);

        $productNewArrival = $productRepository->findByIsNewArrival(1);

        $productSpecialOffer = $productRepository->findByIsSpecialOffer(1);
        //dd([$productNewArrival, $productSpecialOffer]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productNewArrival' => $productNewArrival,
            'productSpecialOffer' => $productSpecialOffer,
            'homeSlider' => $homeSlider,
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

    //shop products 
    #[Route('/shop', name: 'shop')]
    public function shop(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();

        $search = new SearchProduct();
        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findWithSearch($search);     //recovers the products of the research
        }

        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'search' =>$form->createView()
        ]);
    }
}
