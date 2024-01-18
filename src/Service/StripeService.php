<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeService {

    public function __construct(private EntityManagerInterface $em, private UrlGeneratorInterface $router) {}

    public function createOrder(User $user, Product $product) : Order 
    {

        $order = new Order();
        $order->setPurchaser($user);
        $order->setProduct($product);

        $this->em->persist($order);
        $this->em->flush();

        return $order;

    }

    public function getStripeLink(Order $order) : Order
    {

        $stripeClient = new StripeClient($_ENV['STRIPE_PRIVATE_KEY']);

        $url = $this->router->generate('redirect.payment', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $session = $stripeClient->checkout->sessions->create([
            'success_url' => $url,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => (int) $order->getProduct()->getPrice(),
                        'product_data' => [
                            'name' => $order->getProduct()->getTitle(),
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
        ]);

        $order->setStripeReference($session->id);
        $order->setPaymentUrl($session->url);

        $this->em->persist($order);
        $this->em->flush();

        return $order;

    }

}