<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractJsonResponse extends AbstractController
{
    public const FORMAT_JSON = 'json';
    public const RESULT_SUCCESS = 'success';

    public const SUCCESS_STATUSES = [
        Response::HTTP_OK,
        Response::HTTP_CREATED,
        Response::HTTP_ACCEPTED,
        Response::HTTP_NON_AUTHORITATIVE_INFORMATION,
        Response::HTTP_NO_CONTENT,
        Response::HTTP_RESET_CONTENT,
        Response::HTTP_PARTIAL_CONTENT
    ];

    /**
     * @param array|string $data
     * @param int          $status
     * @param array        $headers
     * @param array        $context
     *
     * @return JsonResponse
     */
    protected function json($data, int $status = Response::HTTP_OK, array $headers = [], array $context = []): JsonResponse
    {
        if(is_string($data)) {
            $data = @json_decode($data, true);
        }

        if (in_array($status, self::SUCCESS_STATUSES)) {
            $response = [
                'errorCode' => 0,
                'data'      => $data['data'] ?? $data
            ];
        } else {
            $response = [
                'errorCode' => $status,
                'error'     => $data
            ];
        }

        if (isset($data['meta'])) {
            $response['meta'] = $data['meta'];
            unset($data['meta']);
        }

        return parent::json(
            $response,
            $status,
            $headers,
            $context
        );
    }
}
