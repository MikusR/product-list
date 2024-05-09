<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductCollection;
use App\RedirectResponse;
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
    }

    public function index(): Response
    {
        $products = new ProductCollection();
        try {
            $productsList = $this->database->createQueryBuilder()
                ->select('*')
                ->from('products')
                ->fetchAllAssociative();
        } catch (Exception $e) {
            $productsList = [];
            echo $e->getMessage();
        }

        foreach ($productsList as $product) {
            $products->add(
                new Product(
                    $product['sku'],
                    $product['name'],
                    $product['price'],
                    $product['type'],
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

        try {
            $builder
                ->insert('products')
                ->values([
                    'sku' => ':sku',
                    'name' => ':name',
                    'price' => ':price',
                    'type' => ':type',
                    'atributesJson' => ':atributesJson',
                    'atributesSerialized' => ':atributesSerialized'
                ])
                ->setParameters([
                    'sku' => $product->getSku(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'type' => $product->getType(),
                    'atributesJson' => json_encode($product->getAtributes()),
                    'atributesSerialized' => serialize($product->getAtributes()),
                ])
                ->executeQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function addProduct(): response
    {
        return new ViewResponse('addProduct', [
            'types' => $this->getProductTypes()
        ]);
    }

    public function add(): Response
    {
//        Helper::dump($_POST);
        $atributes = [
            ['productType' => $_POST['productType']],
            ['name' => 'size', 'value' => $_POST['size']],
            ['name' => 'weight', 'value' => $_POST['weight']],
            ['name' => 'height', 'value' => $_POST['height']],
            ['name' => 'width', 'value' => $_POST['width']],
            ['name' => 'length', 'value' => $_POST['length']]
        ];
        $product = new Product(
            $_POST['sku'],
            $_POST['name'],
            (int)$_POST['price'],
            $_POST['productType'],
            $atributes
        );
        $this->save($product);
        \App\Helper::dd($product);
        return new RedirectResponse('/');
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

    public function createTable()
    {
        $schema = $this->database->createSchemaManager();
        try {
            if ($schema->tablesExist('products')) {
                $schema->dropTable('products');
            }
            if (!$schema->tablesExist('products')) {
                $products = new Table('products');
                $products->addColumn('sku', 'string', ['length' => 255]);
                $products->setPrimaryKey(['sku']);
                $products->addUniqueIndex(['sku']);
                $products->addColumn('name', 'string', ['length' => 255]);
                $products->addColumn('price', 'integer');
                $products->addColumn('type', 'string', ['length' => 255]);
                $products->addColumn('atributesJson', 'json');
                $products->addColumn('atributesSerialized', 'text');
                $schema->createTable($products);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function seedTable()
    {
        $this->save(new Product('123', 'Product 1', 100, 'DVD', [['name' => 'color', 'value' => 'red']]));
        $this->save(new Product('456', 'Product 2', 200, 'Book', [['name' => 'color', 'value' => 'blue']]));
        $this->save(
            new Product(
                '789', 'Product 3', 300, 'Furniture',
                [
                    ['name' => 'weight', 'value' => '10kg'],
                    ['name' => 'height', 'value' => '10cm']
                ]
            )
        );
        $this->save(new Product('012', 'Product 4', 400, 'Furniture', [
            ['name' => 'weight', 'value' => '20kg'],
            ['name' => 'height', 'value' => '20cm']
        ]));
    }

    public function migrate()
    {
        $this->createTable();
        $this->seedTable();
    }
}