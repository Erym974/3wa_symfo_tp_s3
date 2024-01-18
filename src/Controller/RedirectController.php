<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\StripeService;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class RedirectController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager)
    {
    }

    #[Route('/redirect/favorites/{product}', name: 'redirect.favorites')]
    public function favorites(#[MapEntity(mapping: ['product' => 'slug'])] Product $product, Request $request): RedirectResponse
    {

        /** @var User */
        $user = $this->getUser();

        if ($user->getFavorites()->contains($product)) {
            $user->removeFavorite($product);
        } else {
            $user->addFavorite($product);
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    #[Route('/redirect/delete/{product}', name: 'redirect.delete.product')]
    public function delete(#[MapEntity(mapping: ['product' => 'slug'])] Product $product, Request $request): RedirectResponse
    {

        /** @var User */
        $user = $this->getUser();


        if ($product->getAuthor() == $user) {
            $product->setDeleted(true);
            $this->manager->persist($product);
            $this->manager->flush();
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    #[Route('/redirect/cancel/{id}', name: 'redirect.cancel.order')]
    public function cancel(Order $order, Request $request): RedirectResponse
    {

        /** @var User */
        $user = $this->getUser();

        $order->setStatus(Order::FAILED);

        $this->manager->persist($order);
        $this->manager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    #[Route('/redirect/buy/{product}', name: 'redirect.buy.product')]
    public function buy(#[MapEntity(mapping: ['product' => 'slug'])] Product $product, StripeService $stripe): RedirectResponse
    {

        /** @var User */
        $user = $this->getUser();

        $order = $stripe->createOrder($user, $product);

        $order = $stripe->getStripeLink($order);

        return $this->redirect($order->getPaymentUrl());
    }

    #[Route('/redirect/payment/{id}', name: 'redirect.payment')]
    public function payment(Order $order): RedirectResponse
    {

        /** @var User */
        $user = $this->getUser();

        $stripe = new StripeClient($_ENV['STRIPE_PRIVATE_KEY']);

        $session = $stripe->checkout->sessions->retrieve($order->getStripeReference());

        switch ($session->status) {
            case 'complete':
                $order->setStatus(Order::PAID);

                $product = $order->getProduct();
                $product->setSelled(true);

                $this->manager->persist($product);

                break;
            case 'open':
                $order->setStatus(Order::PENDING);
                break;
            default:
                $order->setStatus(Order::FAILED);
                break;
        }

        $this->manager->persist($order);
        $this->manager->flush();

        return $this->redirectToRoute('app_order');
    }

    #[Route('/redirect/delete-media/{product}/{media}', name: 'redirect.delete.media')]
    public function delete_media(Product $product, Media $media, Request $request): RedirectResponse
    {

        /** @var User */
        $user = $this->getUser();

        if ($product->getAuthor() == $user || $this->isGranted('ROLE_MANAGER')) {
            $this->manager->remove($media);
            $this->manager->flush();
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
