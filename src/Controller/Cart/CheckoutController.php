<?php

namespace App\Controller\Cart;

use App\Form\CheckoutType;
use Symfony\Component\HttpFoundation\Request;
use App\Services\OrderServices;
use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    private $cartServices;
    private $session;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }

    #[Route('/checkout', name: 'checkout')]
    public function index(Request $request ): Response
    {
        $user = $this->getUser('addresses');
        $cart = $this->cartServices->getFullCart();

        //si le panier est vide alors redirige sur home 
        if(!isset($cart['products'])){
            return $this->redirectToRoute("home");
        }
        //il y a un panier on va chercher si il y a une adresse de renseignée  s'il n'y en a pas on le redirige sur la page adresse new
        if(!$user->getAddresses()->getValues()){
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse avant de continuer');
            return $this->redirectToRoute("address_new");
        }
        //mise en place du formulaire
        $form = $this->createForm(CheckoutType::class,null,['user'=>$user]);          //null,

        $form->handleRequest($request);
        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }

    #[Route('/checkout/confirm', name: 'checkout_confirm')]
    public function confirm(Request $request, OrderServices $orderServices): Response 
    {
        $user = $this->getUser('user');
        $cart = $this->cartServices->getFullCart();
        
        //si le panier est vide alors redirige sur home 
        if(!isset($cart['products'])){
            return $this->redirectToRoute("home");
        }
        //il y a un panier on va chercher si il y a une adresse de renseignée  s'il n'y en a pas on le redirige sur la page adresse new
        if(!$user->getAddresses()->getValues()){
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse avant de continuer');
            return $this->redirectToRoute("address_new");
        }
        //mise en place du formulaire
        $form = $this->createForm(CheckoutType::class,null,['user'=>$user]);          //null,    
        $form->handleRequest($request);

        //est ce que le formulaire est soumis et valide 
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $address = $data['address'];
            $carrier = $data['carrier'];
            $information = $data['informations'];
            
            //save cart
            $cart['checkout'] = $data;
            $reference = $orderServices->saveCart($cart, $user);
            
            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'informations' => $information,
                'reference'=> $reference,
                'checkout' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('checkout');
    }
}
