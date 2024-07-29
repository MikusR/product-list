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
        $data   = [
            'sku'    => $_POST['sku'],
            'name'   => $_POST['name'],
            'price'  => (int)$_POST['price'],
            'type'   => $_POST['productType'],
            'size'   => $_POST['size'],
            'weight' => $_POST['weight'],
            'height' => $_POST['height'],
            'width'  => $_POST['width'],
            'length' => $_POST['length']
        ];
        $errors = $this->validate($data);
        if (empty($errors)) {
            $type = 'App\Models\\'.$_POST['productType'];
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

    public function search(): Response
    {
        $product = $_POST['product'];
        if ($product = '*') {
            return new ViewResponse('index', ['products' => $this->repository->getAll()->getProducts()]);
        }
        $products = $this->repository->search($product);
        if ($products === null) {
            return new ViewResponse('index', ['products' => []]);
        }

        return new ViewResponse('index', ['products' => $products->getProducts()]);
    }

    public function delete(): Response
    {
        if ($_SERVER['HTTP_X_CSRF_TOKEN'] != $_SESSION['csrf_token']) {
            return new JsonResponse(403, ['csrf token error']);
        }
        $list = json_decode(file_get_contents("php://input"));

        foreach ($list as $sku) {
            $product = $this->repository->getProduct($sku);
            if ( ! $product) {
                continue;
            }
            $this->repository->delete($product);
        }

        return new JsonResponse(200, ['OK']);
    }

    public function getProductTypes(): array
    {
        $types = [
            'DVD'       => [
                'name'        => 'DVD',
                'attributes'  => ['size'],
                'description' => 'Please, provide size',
                'unit'        => 'MB'
            ],
            'Book'      => [
                'name'        => 'Book',
                'attributes'  => ['weight'],
                'description' => 'Please, provide weight',
                'unit'        => 'Kg'
            ],
            'Furniture' => [
                'name'        => 'Furniture',
                'attributes'  => ['height', 'width', 'length'],
                'description' => 'Please, provide dimensions',
                'unit'        => 'cm'
            ]
        ];

        return $types;
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
            $errors['csrf_token'] = 'Invalid CSRF token';
        }
        if ($this->repository->getProduct($data['sku'])) {
            $errors['sku'] = 'SKU already exists';
        }
        if ($data['price'] <= 0) {
            $errors['price'] = 'Price must be greater than 0';
        }
        if ($data['size'] != '' && $data['size'] <= 0) {
            $errors['size'] = 'Size must be greater than 0';
        }
        if ($data['weight'] != '' && $data['weight'] <= 0) {
            $errors['weight'] = 'Weight must be greater than 0';
        }
        if ($data['height'] != '' && $data['height'] <= 0) {
            $errors['height'] = 'Height must be greater than 0';
        }
        if ($data['width'] != '' && ($data['width'] <= 0)) {
            $errors['width'] = 'Width must be greater than 0';
        }
        if ($data['length'] != '' && $data['length'] <= 0) {
            $errors['length'] = 'Length must be greater than 0';
        }

        return $errors;
    }
}