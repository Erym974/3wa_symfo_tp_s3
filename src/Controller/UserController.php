<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    #[Route('/user/redirect/{id}', name: 'app_user_redirect')]
    public function redirect_edit(User $user): RedirectResponse
    {
        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function show(User $user, EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        // get products of user selled thank to Order Entity with status paid
        $selled = $user->getProducts()->filter(function (Product $product) {
            return $product->isSelled();
        });

        $edit = $request->query->get('edit') == "1" ? true : false;
        $editForm = null;

        if ($edit && $user == $this->getUser()) {

            $editForm = $this->createForm(UserType::class, $user);

            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {

                $file = $editForm->get('media')->getData();

                if($file) {
                    $media = new Media();
                    $media->setFile($file);
                    $manager->persist($media);
                    $user->setAvatar($media);
                }

                $currentPassword = $editForm->get('currentPassword')->getData();

                if ($currentPassword) {

                    if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                        $this->addFlash('error', 'Mot de passe actuel incorrect');
                        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
                    }

                    $newPassword = $editForm->get('newPassword')->getData();

                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $newPassword
                    );
                    $user->setPassword($hashedPassword);
                }

                $manager->persist($user);
                $manager->flush();



                return $this->redirectToRoute('app_user_redirect', ['id' => $user->getId()]);
            }
        }


        return $this->render('user/show.html.twig', [
            'user' => $user,
            'selled' => $selled,
            'editForm' => $editForm ? $editForm->createView() : null
        ]);
    }
}
