<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AdController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager)
    {
        
    }

    #[Route('/annonces', name: 'app_ad')]
    public function index(): Response
    {
        /** @var User */
        $user = $this->getUser();
        $products = $user->getProducts();

        return $this->render('ad/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/annonces/nouvelle-annonce', name: 'app_new_ad')]
    public function new(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $product = new Product();
        $product->setAuthor($user);

        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()) {

            $files = $editForm->get('medias')->getData();
            
            foreach ($files as $file) {
                $media = new Media();
                $media->setFile($file);
                $this->manager->persist($media);
                $product->addPicture($media);
            }

            $this->manager->persist($product);
            $this->manager->flush();

            return $this->redirectToRoute('app.categories.product', [
                'category' => $product->getCategory(),
                'product' => $product,
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'editForm' => $editForm->createView(),
        ]);
    }
}
