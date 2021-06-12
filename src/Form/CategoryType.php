<?php

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
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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

                throw new TransformationFailedException("Cannot find category with $id.");
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
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
