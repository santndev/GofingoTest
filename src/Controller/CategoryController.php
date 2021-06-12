<?php

namespace App\Controller;

use App\Form\CreateCategoryType;
use App\Form\Utils\FormErrorParser;
use App\Service\CategoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/category", name="category")
 */
class CategoryController extends AbstractJsonResponse
{
    /**
     * @var CategoryService
     */
    private CategoryService $categoryService;

    public function __construct(
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("", name="All", methods={"GET"})
     * @param SerializerInterface $serializer
     *
     * @return Response
     */
    public function index(SerializerInterface $serializer): Response
    {
        /** @var array $list */
        $list = $this->categoryService->getAll();
        return $this->json(
            $serializer->serialize(
                $list,
                'json'
            )
        );
    }

    /**
     * @Route("", name="New", methods={"POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        $form    = $this->createForm(CreateCategoryType::class);
        $form->submit($payload);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $category = $form->getData();
                try {
                    $this->categoryService->createOne($category);
                } catch (\Exception $exception) {
                    return $this->json(
                    // TODO: shouldn't display exception here.
                        $exception->getMessage(),
                        Response::HTTP_INTERNAL_SERVER_ERROR
                    );
                }
            } else {
                $errors = FormErrorParser::arrayParse($form);
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(
            "success", Response::HTTP_CREATED
        );
    }
}
