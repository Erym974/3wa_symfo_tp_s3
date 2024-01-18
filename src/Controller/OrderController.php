<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class OrderController extends AbstractController
{
    #[Route('/commandes', name: 'app_order')]
    public function index(): Response
    {

        /** @var User */
        $user = $this->getUser();
        $orders = $user->getOrders();

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
