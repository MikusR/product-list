<?php

declare(strict_types=1);

namespace App;


use App\Repositories\MySqlRepository;


class Helper
{
    private MySqlRepository $repository;

    public function __construct()
    {
        $this->repository = new MySqlRepository();
    }

    public static function info()
    {
        echo "<pre>";
        echo phpinfo();
    }

    public static function dd($value)
    {
        echo '<pre>';
        var_dump($value);
        die;
    }

    public static function dump($value)
    {
        echo '<pre>';
        var_dump($value);
    }

    public static function test()
    {
    }


    public function seedTable()
    {
        $model = $this->repository->buildModel(
            ['sku' => '123', 'name' => 'Matrix', 'price' => 100, 'type' => 'DVD', 'size' => '600']
        );
        $this->repository->save($model);
        $model = $this->repository->buildModel(
            ['sku' => '456', 'name' => 'Matrix 2', 'price' => 110, 'type' => 'DVD', 'size' => '700']
        );
        $this->repository->save($model);
        $model = $this->repository->buildModel(
            [
                'sku' => 'furn123',
                'name' => 'Table',
                'price' => 440,
                'type' => 'Furniture',
                'height' => '100',
                'width' => '200',
                'length' => '300'
            ]
        );
        $this->repository->save($model);
        $model = $this->repository->buildModel(
            [
                'sku' => 'book124',
                'name' => 'Boring',
                'price' => 560,
                'type' => 'Book',
                'weight' => '10',
            ]
        );
        $this->repository->save($model);
        $model = $this->repository->buildModel(
            [
                'sku' => 'furn124',
                'name' => 'Chair',
                'price' => 60,
                'type' => 'Furniture',
                'height' => '10',
                'width' => '560',
                'length' => '110'
            ]
        );
        $this->repository->save($model);
        $model = $this->repository->buildModel(
            ['sku' => 'zdvd256', 'name' => 'Matrix 3', 'price' => 210, 'type' => 'DVD', 'size' => '650']
        );
        $this->repository->save($model);
    }

    public function migrate(): Response
    {
        $this->repository->createTable();
        $this->seedTable();
        return new RedirectResponse('/');
    }
}

//foreach ($argv as $arg) {
//    method_exists(Helper::class, $arg) and call_user_func([Helper::class, $arg]);
//}