<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

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
    public function product(#[MapEntity(mapping: ['category' => 'slug'])] Category $category, #[MapEntity(mapping: ['product' => 'slug'])] Product $product): Response
    {

        throw new Exception("Not yet implemented");

        return $this->render('category/index.html.twig');
    }

}
