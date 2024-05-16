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
        return new ViewResponse('addProduct');
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
        $data = [];
        $data['sku'] = $_POST['sku'];
        $data['name'] = $_POST['name'];
        $data['price'] = (int)$_POST['price'];
        $data['type'] = $_POST['productType'];
        $data['atributes'] = $atributes;
        $type = 'App\Models\Product'.$_POST['productType'];

        if (class_exists($type)) {
            $product = new $type($data);
            $this->repository->save($product);
        }

        return new RedirectResponse('/');
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

}