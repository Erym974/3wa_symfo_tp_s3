<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(): Response
    {

        /** @var User */
        $user = $this->getUser();
        $favorites = $user->getFavorites();

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }
}
