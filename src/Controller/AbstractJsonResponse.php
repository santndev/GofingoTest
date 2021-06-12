<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractJsonResponse extends AbstractController
{

    /**
     * @param array|string $data
     * @param int          $status
     * @param array        $headers
     * @param array        $context
     *
     * @return JsonResponse
     */
    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        try {
            $json = json_decode($data, true);
            if ($json) {
                $data = $json;
            }
        } catch (\Exception $e) {
        }
        if (in_array($status, [200, 201, 202, 203, 204, 205, 206])) {
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
