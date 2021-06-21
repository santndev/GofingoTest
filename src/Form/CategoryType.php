<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($entity) {
                return $entity;
            },
            function (?int $id) use ($options) {
                if (!$id) {
                    return null;
                }

                $entity = $this->categoryRepository->find($id);
                if ($entity) {
                    return Category::class === $options['class'] ? $entity : $entity->getId();
                }

                throw new TransformationFailedException("Cannot find category with id $id.");
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'class'           => Category::class,
            'invalid_message' => 'Category not found.',
            'empty_data'      => null,
            'compound'        => false,
        ]);
    }
}
