<?php

namespace App\Controller\Stripe;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\CartServices;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/stripe-payment-success/{StripeCheckoutSessionId}', name: 'stripe_payment_success')]
    public function index(?Order $order, CartServices $cartServices,
    EntityManagerInterface $manager): Response
    {
        //dd($order);
        //s'il n'y a pas de commande ou si la commande n'appartient pas a la personne connectée redirige sur home 
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute("home");
        }
        //si la commande n'est payée
        if(!$order->getIsPaid()){
            //commande payée
            $order->setIsPaid(true);
            $manager->flush();                  //on syncronise les modifs 
            $cartServices->deleteCart();        //supprime tout le panier
            //un mail au client

            //
        }
        return $this->render('stripe/stripe_success_payment.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
            'order' => $order
        ]);
    }
}
