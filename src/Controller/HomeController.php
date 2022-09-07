<?php

namespace App\Controller;

use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController

{
    private $productRepository;
    private $homeSliderRepository;

    public function __construct(ProductRepository $productRepository,
    HomeSLiderRepository $homeSliderRepository)
    {
        $this->productRepository = $productRepository;
        $this->homeSliderRepository = $homeSliderRepository;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        $homeSlider = $this->homeSliderRepository->findBy(['isDisplayed'=>true]);
        //dd($homeSlider);

        $productNewArrival = $this->productRepository->findByIsNewArrival(1);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productNewArrival' => $productNewArrival,
            'homeSlider' => $homeSlider,
        ]);
    }

    //details product
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
    public function shop(Request $request): Response
    {
        $products = $this->productRepository->findAll();

        $search = new SearchProduct();
        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $products = $this->productRepository->findWithSearch($search);     //recovers the products of the research
        }

        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'search' =>$form->createView()
        ]);
    }
}
