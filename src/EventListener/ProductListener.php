<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product $product
     */
    public function postPersist(Product $product): void
    {
        dd($product);
    }

    /**
     * @param Product $product
     */
    public function postUpdate(Product $product): void
    {
        dd($product);
    }
}
