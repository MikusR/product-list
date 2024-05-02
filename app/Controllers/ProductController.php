<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helper;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Response;
use App\ViewResponse;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Table;
use PDO;

class ProductController
{
    protected Connection $database;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => $_ENV['DBNAME'],
            'user' => $_ENV['DBUSER'],
            'password' => $_ENV['PASSWORD'],
            'host' => $_ENV['HOST'],
            'driver' => $_ENV['DRIVER'],
            'port' => (int)$_ENV['PORT'],
            'driverOptions' =>
                [PDO::ATTR_EMULATE_PREPARES => false]

        ];

        try {
            $this->database = DriverManager::getConnection($connectionParams);
            $this->database->connect();
        } catch (Exception $e) {
            $_SESSION['error'] = [
                'status' => true,
                'message' => "Can't connect to database",
                'description' => $e->getMessage()
            ];
        }

        $schema = $this->database->createSchemaManager();
        if (!$schema->tablesExist('products')) {
            $products = new Table('products');
            $products->addColumn('sku', 'string', ['length' => 255]);
            $products->setPrimaryKey(['sku']);
            $products->addUniqueIndex(['sku']);
            $products->addColumn('name', 'string', ['length' => 255]);
            $products->addColumn('price', 'integer');
            $products->addColumn('atributesJson', 'json');
            $products->addColumn('atributesSerialized', 'text');
            $schema->createTable($products);
        }
//        $this->save(new Product('123', 'Product 1', 100, [['name' => 'color', 'value' => 'red']]));
//        $this->save(new Product('456', 'Product 2', 200, [['name' => 'color', 'value' => 'blue']]));
//        $this->save(
//            new Product(
//                '789', 'Product 3', 300,
//                [
//                    ['name' => 'weight', 'value' => '10kg'],
//                    ['name' => 'height', 'value' => '10cm']
//                ]
//            )
//        );
//        $this->save(new Product('012', 'Product 4', 400, [
//            ['name' => 'weight', 'value' => '20kg'],
//            ['name' => 'height', 'value' => '20cm']
//        ]));
    }

    public function index(): Response
    {
        $products = new ProductCollection();
        $productsList = $this->database->createQueryBuilder()
            ->select('*')
            ->from('products')
            ->fetchAllAssociative();

        foreach ($productsList as $product) {
            $products->add(
                new Product(
                    $product['sku'],
                    $product['name'],
                    $product['price'],
                    unserialize($product['atributesSerialized'])
                )
            );
        }

        return new ViewResponse(
            'index',
            ['products' => $products->getProducts()]
        );
    }

    public function save(Product $product)
    {
        $builder = $this->database->createQueryBuilder();

        $builder
            ->insert('products')
            ->values([
                'sku' => ':sku',
                'name' => ':name',
                'price' => ':price',
                'atributesJson' => ':atributesJson',
                'atributesSerialized' => ':atributesSerialized'
            ])
            ->setParameters([
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'atributesJson' => json_encode($product->getAtributes()),
                'atributesSerialized' => serialize($product->getAtributes()),
            ])
            ->executeQuery();
    }
}