<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager)
    {
        
    }

    // #[Route('/categories', name: 'category.index')]
    // public function index() : Response
    // {
    //     return $this->render('category/index.html.twig', [
    //         'categories' => $this->manager->getRepository(Category::class)->findAll(),
    //     ]);
    // }

    

    #[Route('/categories/{category?}', name: 'app.categories')]
    public function show(#[MapEntity(mapping: ['category' => 'slug'])] ?Category $category): Response
    {

        if($category === null) {
            $category = $this->manager->getRepository(Category::class)->findOneBy([]);
            return $this->redirectToRoute('app.categories', [
                'category' => $category
            ]);
        }

        return $this->render('category/index.html.twig', [
            'current_category' => $category,
            'products' => $category->getProducts(),
            'categories' => $this->manager->getRepository(Category::class)->findAll(),
        ]);
    }

    #[Route('/categories/{category}/{product}', name: 'app.categories.product')]
    public function product(#[MapEntity(mapping: ['category' => 'slug'])] Category $category, #[MapEntity(mapping: ['product' => 'slug'])] Product $product, Request $request): Response
    {

        /** @var User */
        $user = $this->getUser();

        $edit = $request->query->get('edit') == "1" ? true : false;

        $editForm = null;

        if($product->getCategory() !== $category) {
            return $this->redirectToRoute('app.categories.product', [
                'category' => $product->getCategory(),
                'product' => $product
            ]);
        }

        if($edit && $user == $product->getAuthor()) {

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
                    'edit' => 0,
                ]);
            }

        }

        return $this->render('category/show.html.twig', [
            'current_category' => $category,
            'product' => $product,
            'editForm' => $editForm ? $editForm->createView() : null,
        ]);
    }

}
