<?php

namespace App\Controller\Stripe;

use App\Entity\Cart;
use App\Services\OrderServices;
use App\Services\CartServices;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeCheckoutSessionController extends AbstractController
{
    #[Route('/create-checkout-session/{reference}', name: 'create_checkout_session')]
    public function index(?Cart $cart, OrderServices $orderServices,
    EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $user = $this->getUser();       //recuperer l'email de l'utilisateur connecté
        if(!$cart){
             return $this->redirectToRoute('home');
        }

        $order = $orderServices->createOrder($cart);
        Stripe::setApiKey('sk_test_51LPktXCSZL0O8yPqOPfQ2sCsnk9EJIclxrESMKApETiWMSxU6Oi7Yla2XyyQ42KHSth7SEjqadxfitkzZ47y9uL400vO0NkPIc');

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $orderServices->getLineItems($cart),
            'mode' => 'payment',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . 'stripe-payment-cancel/{CHECKOUT_SESSION_ID}',
            'success_url' => $_ENV['YOUR_DOMAIN'] . 'stripe-payment-success/{CHECKOUT_SESSION_ID}',
            
            ]);

            $order->setStripeCheckoutSessionId($checkout_session->id);
            $entityManagerInterface->flush();

        return $this->json(['id' => $checkout_session->id]);
    }
}
