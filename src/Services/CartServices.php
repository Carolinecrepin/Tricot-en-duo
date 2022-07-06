<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartServices {

    private $session;
    private $productRepository;
    private $tva = 0.2;

    public function __construct(RequestStack $session , ProductRepository $productRepository)
    {
        $this->requestStack = $session;
        $this->productRepository = $productRepository;
    }

    //ajouter au panier un produit
    public function addToCart($id)
    {
        $cart = $this->getCart();   //on recupère le panier
        if(isset($cart[$id])){
            //produit deja dans le panier
            $cart[$id]++;
        }else{
            //produit n'est pas encore dans le panier
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }

    //supprimer du panier un produit
    public function deleteFromCart($id)
    {
        $cart = $this->getCart();

        if(isset($cart[$id])){
            //produit deja dans le panier
            if($cart[$id] > 1){
                //produit existe plus d'une fois
                $cart[$id] --;
            }else{
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }
    }

    //supprimer tout le panier
    public function deleteCart()
    {
        $this->updateCart([]);
    }

    //supprimer la quantité (toute la quantité) d'un produit
    public function deleteAllToCart($id)
    {
        $cart = $this->getCart();

        if(isset($cart[$id])){
            //produit deja dans le panier
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    //mettre a jour le panier
    public function updateCart($cart)
    {
        $session = $this->requestStack->getSession();
        $session->set('cart', $cart);
        $session->set('cartData', $this->getFullCart());
    }

    //recuperer le panier
    public function getCart()
    {
        $session = $this->requestStack->getSession();
        return $session->get('cart', []);
    }

    //recuperer tous les produits du panier
    public function getFullCart()
    {
        $cart = $this->getCart();

        $fullCart = [];
        $quantity_cart = 0;     //la quantité d'articles du panier
        $subTotal = 0;          // montant total du panier

        foreach ($cart as $id => $quantity){
            $product = $this->productRepository->find($id);

            if($product){
                //produit récupéré avec succès
                $fullCart['products'] [] =
                [
                    'quantity' => $quantity,
                    'product' => $product,
                ];

                //dd($fullCart);
                $quantity_cart += $quantity;
                $subTotal += $quantity * $product->getPrice()/100;      //on incremente le sous total avec le prix du produit x sa quantité /100
            }else{
                //id incorrect
                $this->deleteFromCart($id);
            }
        }
        //clé data valeur un tableau avec quantite, sous total ht , taxe et sous total ttc
        $fullCart['data'] = [
            "quantity_cart" => $quantity_cart,
            "subTotalHT" => $subTotal,
            "Taxe" => round($subTotal * $this->tva,2),
            "subTotalTTC" => round(($subTotal + ($subTotal * $this->tva)), 2)
        ];
        return $fullCart;
        //dd($fullCart);
    }
}

