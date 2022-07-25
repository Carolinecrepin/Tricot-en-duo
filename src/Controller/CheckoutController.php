<?php

namespace App\Controller;

use App\Form\CheckoutType;
use Symfony\Component\HttpFoundation\Request;
use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function index(CartServices $cartServices, Request $request ): Response
    {
        $user = $this->getUser();
        //dd($user);
        $cart = $cartServices->getFullCart();
        //dd($cart);
        //si le panier est vide alors redirige sur home 
        if(!$cart){
            return $this->redirectToRoute("home");
        }
        //il y a un panier on va chercher si il y a une adresse de renseignée  s'il n'y en a pas on le redirige sur la page adresse new
        if(!$user->getAddresses()->getValues()){
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse avant de continuer');
            return $this->redirectToRoute("address_new");
        }
        //mise en place du formulaire
        $form = $this->createForm(CheckoutType::class,['user'=>$user]);          //null,
        
        $form->handleRequest($request);
        //dd($form);

        //est ce que le formulaire est soumis et valide


        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }
}
