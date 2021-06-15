<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private CategoryRepository $categoryRepository;

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
