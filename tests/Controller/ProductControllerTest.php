<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends ApiTestCase
{
    public function testGetAllSuccess()
    {
        $response = $this->client->request("GET", '/product');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostError()
    {
        $response = $this->client->request("POST", '/product');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $response = $this->client->request("POST", '/product', [
            'eid'        => 1,
            'categories' => [1]
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testPostSuccess()
    {
        $r        = time();
        $cate     = [
            'eid'   => $r,
            'title' => "C $r"
        ];
        $response = $this->client->request("POST", '/category', ['json' => $cate]);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $response   = $this->client->request("GET", '/category');
        $categories = json_decode($response->getBody()->getContents(), true);

        if (count($categories['data']) > 0) {
            $category = $categories['data'][0];
            $prod     = [
                'eid'        => $r,
                'title'      => "p $r",
                'price'      => 12.123,
                'categories' => [$category['id']]
            ];
        } else {
            $prod = [
                'eid'   => $r,
                'title' => "p $r",
                'price' => 12.123
            ];
        }

        $response = $this->client->request("POST", '/product', ['json' => $prod]);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateSuccess()
    {
        $r    = time();
        $prod = [
            'eid'   => $r,
            'title' => "p $r",
            'price' => 12.123
        ];
        $this->client->request("POST", '/product', ['json' => $prod]);

        $response = $this->client->request("GET", '/product');
        $list     = json_decode($response->getBody()->getContents(), true);
        if (count($list['data']) > 0) {
            $pId      = $list['data'][0]['id'];
            $response = $this->client->request("PATCH", "/product/$pId", [
                'json' => [
                    'title' => "s $r"
                ]
            ]);
            $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        }

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteSuccess()
    {
        $r    = time();
        $prod = [
            'eid'   => $r,
            'title' => "p $r",
            'price' => 12.123
        ];
        $this->client->request("POST", '/product', ['json' => $prod]);

        $response = $this->client->request("GET", '/product');
        $list     = json_decode($response->getBody()->getContents(), true);
        if (count($list['data']) > 0) {
            $pId      = $list['data'][0]['id'];
            $response = $this->client->request("DELETE", "/product/$pId");
            $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        }
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
