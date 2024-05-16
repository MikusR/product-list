<?php

declare(strict_types=1);

namespace App\Controllers;

use App\JsonResponse;
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
        $types = $this->getProductTypes();
        return new ViewResponse('addProduct', ['types' => $types]);
    }

    public function store(): Response
    {
        $errors = [];
        $data = [
            'sku' => $_POST['sku'],
            'name' => $_POST['name'],
            'price' => (int)$_POST['price'],
            'type' => $_POST['productType'],
            'size' => $_POST['size'],
            'weight' => $_POST['weight'],
            'height' => $_POST['height'],
            'width' => $_POST['width'],
            'length' => $_POST['length']
        ];
        if ($this->repository->getProduct($data['sku'])) {
            $errors['sku'] = 'SKU already exists';
        }

        if (empty($errors)) {
            $type = 'App\Models\Product'.$_POST['productType'];
            if (class_exists($type)) {
                $product = new $type($data);
                $this->repository->save($product);
            }
            return new RedirectResponse('/');
        }

        return new ViewResponse(
            'addProduct',
            ['types' => $this->getProductTypes(), 'errors' => $errors, 'data' => $data]
        );
    }

    public function delete(): Response
    {
        $list = json_decode(file_get_contents("php://input"));

        foreach ($list as $sku) {
            $product = $this->repository->getProduct($sku);
            if (!$product) {
                continue;
            }
            $this->repository->delete($product);
        }

        return new JsonResponse(200, ['OK']);
    }

    public function getProductTypes(): array
    {
        $types = [
            'DVD' => ['name' => 'DVD', 'atributes' => ['size'], 'description' => 'Please, provide size in MB'],
            'Book' => ['name' => 'Book', 'atributes' => ['weight'], 'description' => 'Please, provide weight in Kg'],
            'Furniture' => [
                'name' => 'Furniture',
                'atributes' => ['height', 'width', 'length'],
                'description' => 'Please, provide dimensions in cm'
            ]
        ];
        return $types;
    }
}