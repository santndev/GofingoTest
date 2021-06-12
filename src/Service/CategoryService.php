<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ) {
        $this->entityManager      = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @param int $productId
     *
     * @return Category|null
     */
    public function getOne(int $categoryId): ?Category
    {
        return $this->categoryRepository->find($categoryId);
    }

    public function createOne(Category $category)
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}
