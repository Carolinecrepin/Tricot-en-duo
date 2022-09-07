<?php

namespace App\Services;

use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerServices {
    private $manager;
    private $productRepository;

    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository)
    {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
    }

    //methode pour decrémenter la quantité en stock quand un user achete un produit
    public function deStock(Order $order)
    {
        $orderDetails = $order->getOrderDetails()->getValues();

        //on va parcourir le tableau et on réajuste le stock 
        foreach ($orderDetails as $key => $details) {
            //on recupère le produit ayant le nom dans le orderdetails pour s'en servir et decrémenter la quantité
            $product = $this->productRepository->findByName($details->getProductName())[0];
            $newQuantity = $product->getQuantity() - $details->getQuantity();
            $product->setQuantity($newQuantity);
            $this->manager->flush();
        }
    }

}