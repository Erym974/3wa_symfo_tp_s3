<?php

namespace App\EntityListener;

use App\Entity\Product;
use Cocur\Slugify\Slugify;

class ProductListener
{

    private Slugify $slugify;

    public function __construct()
    {
        $this->slugify = new Slugify();
    }

    public function prePersist(Product $product)
    {
        $product->setSlug($this->slugify->slugify($product->getTitle()));
    }

    public function preUpdate(Product $product) 
    {
        $product->setSlug($this->slugify->slugify($product->getTitle()));
        $product->setUpdatedAt(new \DateTimeImmutable());
    }

}