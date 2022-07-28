<?php

namespace App\Controller\Stripe;

use App\Services\CartServices;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeCheckoutSessionController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function index(CartServices $cartServices): JsonResponse
    {
        $cart = $cartServices->getFullCart();
        Stripe::setApiKey('sk_test_51LPktXCSZL0O8yPqOPfQ2sCsnk9EJIclxrESMKApETiWMSxU6Oi7Yla2XyyQ42KHSth7SEjqadxfitkzZ47y9uL400vO0NkPIc');
        
        //$YOUR_DOMAIN = 'http://localhost:8000'
        $line_items = [];
        foreach ($cart['products'] as $data_product) {
            $product = $data_product['product'];
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice()*100,
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$_ENV['YOUR_DOMAIN'] .'uploads/products/'. $product->getPicture1()],
                    ],
                ],
                'quantity' => $data_product['quantity'],
            ];
            //dd($product);
        }
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . 'stripe-payment-cancel',
            'success_url' => $_ENV['YOUR_DOMAIN'] . 'stripe-payment-success',
            
            ]);

        return $this->json([]);
    }
}
