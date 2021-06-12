<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\Utils\FormErrorParser;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/product", name="product")
 */
class ProductController extends AbstractJsonResponse
{
    /**
     * @var ProductService
     */
    private ProductService $productService;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(
        ProductService $productService,
        SerializerInterface $serializer
    ) {
        $this->productService = $productService;
        $this->serializer     = $serializer;
    }

    /**
     * @Route("", name="All", methods={"GET"})
     * @return Response
     */
    public function getAll(): Response
    {
        /** @var array $list */
        $list = $this->productService->getAll();
        return $this->json(
            $this->serializer->serialize(
                $list,
                'json'
            )
        );
    }

    /**
     * @Route("/{productId}", name="Detail", methods={"GET"}, requirements={"id"="\d+"})
     * @param string $productId
     *
     * @return Response
     */
    public function getDetail(string $productId): Response
    {
        /** @var Product|null $product */
        $product = $this->productService->getOne((int)$productId);
        if (!$product) {
            return $this->json(
                "Product not found", Response::HTTP_OK
            );
        }

        return $this->json(
            $this->serializer->serialize(
                $product,
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
        $form    = $this->createForm(ProductType::class);
        $form->submit($payload);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $product = $form->getData();
                try {
                    $this->productService->createOne($product);
                } catch (\Exception $exception) {
                    return $this->json(
                        $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR
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

    /**
     * @Route("/{productId}", name="Update", methods={"PATCH"})
     * @param string  $productId
     * @param Request $request
     *
     * @return Response
     */
    public function update(string $productId, Request $request): Response
    {
        /** @var Product|null $product */
        $product = $this->productService->getOne($productId);
        if (!$product) {
            return $this->json(
                "Product not found!", Response::HTTP_BAD_REQUEST
            );
        }
        $payload = json_decode($request->getContent(), true);
        $form    = $this->createForm(ProductType::class, $product);

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($payload, $clearMissing);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $product = $form->getData();
                try {
                    $this->productService->updateOne($product);
                } catch (\Exception $exception) {
                    return $this->json(
                        $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR
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

    /**
     * @Route("/{productId}", name="Delete", methods={"DELETE"})
     * @param string $productId
     *
     * @return Response
     */
    public function delete(string $productId): Response
    {
        /** @var Product $product */
        $product = $this->productService->getOne($productId);
        if ($product) {
            try {
                $this->productService->delete($product);
            } catch (\Exception $e) {
                return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return $this->json("success", Response::HTTP_OK);
    }
}
