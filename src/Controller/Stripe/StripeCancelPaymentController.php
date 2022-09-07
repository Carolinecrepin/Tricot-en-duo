<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCancelPaymentController extends AbstractController
{
    #[Route('/stripe-payment-cancel/{StripeCheckoutSessionId}', name: 'stripe_payment_cancel')]
    public function index(?Order $order): Response
    {
        //s'il n'y a pas de commande ou si la commande n'appartient pas a la personne connectée redirige sur home 
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute("home");
        }
        
        return $this->render('stripe/stripe_cancel_payment.html.twig', [
            'controller_name' => 'StripeCancelPaymentController',
            'order' => $order
        ]);
    }
}
