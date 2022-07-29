<?php
namespace App\Services;

use App\Entity\OrderDetails;
use App\Entity\Order;
use App\Entity\CartDetails;
use App\Entity\Cart;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderServices{
    private $manager;
    private $productRepository;

    public function __construct(EntityManagerInterface $manager,ProductRepository $productRepository)
    {
        $this->manager = $manager;
        $this-> productRepository = $productRepository;
    }

    //methode pour creer une commande 
    public function createOrder($cart)
    {
        $order = new Order();
        $order->setReference($cart->getReference())
              ->setCarrierName($cart->getCarrierName())
              ->setCarrierPrice($cart->getCarrierPrice())
              ->setFullname($cart->getFullname())
              ->setDeliveryAddress($cart->getDeliveryAddress())
              ->setMoreInformations($cart->getMoreInformations())
              ->setQuantity($cart->getQuantity())
              ->setSubtotalHT($cart->getSubtotalHT())
              ->setTaxe($cart->getTaxe())
              ->setSubtotalTTC($cart->getSubtotalTTC())
              ->setUser($cart->getUser())
              ->setCreatedAt($cart->getCreatedAt());
        $this->manager->persist($order);

        $products = $cart->getCartDetails()->getValues();

        foreach ($products as $cart_product){
            $orderDetails = new OrderDetails();
            $orderDetails->setOrders($order)
                        ->setProductName($cart_product->getProductName())
                        ->setProductPrice($cart_product->getProductPrice())
                        ->setQuantity($cart_product->getQuantity())
                        ->setSubTotalHT($cart_product->getSubTotalHT())
                        ->setSubTotalTTC($cart_product->getSubTotalTTC())
                        ->setTaxe($cart_product->getTaxe());
            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;

    }

    public function getLineItems($cart)
    {
        $cartDetails = $cart->getCartDetails();     //on recupère de le detail du panier

        $line_items = [];
        foreach ($cartDetails as $details) {
            $product = $this->productRepository->findOneByName($details->getProductName());      //permet de recupérer le produit par son nom
        
            //recupère le detail du panier (produits)
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$_ENV['YOUR_DOMAIN'] .'uploads/products/'],
                    ],
                ],
                'quantity' => $details->getQuantity(),
            ];
        }

        //ajouter le données concernant la taxe et transporteur
        //Carrier
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $cart->getCarrierPrice(),
                    'product_data' => [
                        'name' => 'Livraison (' . $cart->getCarrierName() . ')',
                        'images' => [$_ENV['YOUR_DOMAIN'] .'uploads/products/'],
                    ],
                ],
                'quantity' => 1,
            ];
        //Taxe
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $cart->getTaxe()*100,
                    'product_data' => [
                        'name' => 'TVA (20%)',
                        'images' => [$_ENV['YOUR_DOMAIN'] .'uploads/products/'. $product->getPicture1()],
                    ],
                ],
                'quantity' => 1,
            ];

            return $line_items;
    }

    //methode pour sauvegarder un panier en bdd
    public function saveCart($data, $user)
    {
        /*[
            'products' => [],
            'data' => [],
            'checkout' => [
                'address' => objet address,
                'carrier' => dfge
            ],
        ]
        */
        $cart = new Cart();
        $reference = $this->generatedUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $informations = $data['checkout']['informations'];

        $cart->setReference($reference)
             ->setCarrierName($carrier->getName())
             ->setCarrierPrice($carrier->getPrice())
             ->setFullname($address->getFullname())
             ->setDeliveryAddress($address)
             ->setMoreInformations($informations)
             ->setQuantity($data['data']['quantity_cart'])
             ->setSubTotalHT($data['data']['subTotalHT'])
             ->setTaxe ($data['data']['Taxe'])
                ->setSubTotalTTC(round(($data['data']['subTotalTTC']+$carrier->getPrice()/100),2))
                ->setUser($user)
                ->setCreatedAt(new \DatetimeImmutable());

        $this->manager->persist($cart);

        $cart_details_array = [];

        foreach ($data['products'] as $products){
            $cartDetails = new CartDetails();

            $subTotal = $products['quantity'] * $products['product']->getPrice()/100;

            $cartDetails->setCarts($cart)
                        ->setProductName($products['product']->getName())
                        ->setProductPrice($products['product']->getPrice()/100)
                        ->setQuantity($products['quantity'])
                        ->setSubTotalHT($subTotal)
                        ->setSubTotalTTC($subTotal*1.2)
                        ->setTaxe($subTotal*0.2);
            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }

        $this->manager->flush();

        return $reference;

    }

    //methode pour avoir une clé unique pour sauvegarder les références
    public function generatedUuid(){
        //initialise le générateur de nombres aléatoires Mersenne Twister
        mt_srand((double)microtime()*100000);

        //strtoupper : renvoie une chaine en majuscules
        //uniqid : genère un identifiant unique
        $charid = strtoupper(md5(uniqid(rand(), true)));

        //générer une chaine d'un octet à partir d'un nombre
        $hyphen = chr(45);

        //substr : retourne un segment de chaine
        $uuid = ""
        .substr($charid, 0,8).$hyphen
        .substr($charid, 8,4).$hyphen
        .substr($charid, 12,4).$hyphen
        .substr($charid, 16,4).$hyphen
        .substr($charid, 20,12);

        return $uuid;
    }
}