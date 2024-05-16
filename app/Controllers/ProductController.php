<?php

declare(strict_types=1);

namespace App\Controllers;

use App\JsonResponse;
use App\Models\Product;
use App\RedirectResponse;
use App\Repositories\MySqlRepository;
use App\Response;
use App\ViewResponse;

class ProductController
{
    private MySqlRepository $repository;

    public function __construct()
    {
        $this->repository = new MySqlRepository();
    }

    public function index(): Response
    {
        $products = $this->repository->getAll();
        return new ViewResponse(
            'index',
            ['products' => $products->getProducts()]
        );
    }

    public function create(): response
    {
        return new ViewResponse('addProduct', [
            'types' => $this->getProductTypes()
        ]);
    }

    public function store(): Response
    {
        $atributes = [
            ['name' => 'size', 'value' => $_POST['size']],
            ['name' => 'weight', 'value' => $_POST['weight']],
            ['name' => 'height', 'value' => $_POST['height']],
            ['name' => 'width', 'value' => $_POST['width']],
            ['name' => 'length', 'value' => $_POST['length']]
        ];
//
//            $_POST['sku'],
//            $_POST['name'],
//            (int)$_POST['price'],
//            $_POST['productType'],
//            $atributes

        $product = new Product();
        $this->repository->save($product);
        return new RedirectResponse('/');
    }

    public function delete(): Response
    {
        $list = json_decode(file_get_contents("php://input"));
        try {
            foreach ($list as $sku) {
                $product = $this->repository->getProduct($sku);
                if (!$product) {
                    continue;
                }
                $this->repository->delete($product);
            }
        } catch (Exception $e) {
            \App\Helper::dump($e->getMessage());
        }

        return new JsonResponse(200, ['OK']);
    }

    public function getProductTypes(): array
    {
        $types = [
            'DVD' => ['name' => 'dvd', 'atributes' => ['size']],
            'Book' => ['name' => 'book', 'atributes' => ['weight']],
            'Furniture' => ['name' => 'furniture', 'atributes' => ['height', 'width', 'length']]
        ];
        return $types;
    }


}