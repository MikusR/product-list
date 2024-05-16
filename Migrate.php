<?php

declare(strict_types=1);

use App\Configuration;
use App\Repositories\MySqlRepository;

require_once __DIR__.'/vendor/autoload.php';

class Migrate
{
    private MySqlRepository $repository;

    public function __construct()
    {
        $config = new Configuration();
        $config->prepareSecrets();
        $this->repository = new MySqlRepository();
    }

    public function migrate()
    {
        $this->repository->createTable();
        $this->seedTable();
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

}

$migration = new Migrate();
$migration->migrate();