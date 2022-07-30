<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'account')]
    public function index(OrderRepository $orderRepo): Response
    {
        $orders = $orderRepo->findBy(['isPaid' => true, 'user'=>$this->getUser()],['id'=>'DESC']);

        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    //pour voir le detail d'une commande
    #[Route('/order/{id}', name: 'account_order_details')]
    public function show(?Order $order): Response
    {
        //s'il n'y a pas de commande || si la commande n'appartient pas au user connecté || si la commande n'est pas payée
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute("home");
        }
        if(!$order->getIsPaid()){
            return $this->redirectToRoute("account");
        }
        return $this->render('account/detail_order.html.twig', [
            'order' => $order,
        ]);
    }
}
