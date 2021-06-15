<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private ProductRepository $productRepository;

    private CategoryRepository $categoryRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->entityManager      = $entityManager;
        $this->productRepository  = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(): array
    {
        return $this->productRepository->findAll();
    }

    public function getOne(int $productId): ?Product
    {
        return $this->productRepository->find($productId);
    }

    public function createOne(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function updateOne(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function delete(Product $product)
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
