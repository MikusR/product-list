<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helper;
use App\Models\Product;
use App\Models\ProductCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Table;
use PDO;

class MySqlRepository
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

    public function getAll(): ProductCollection
    {
        try {
            $productsList = $this->database->createQueryBuilder()
                ->select('*')
                ->from('products')
                ->fetchAllAssociative();
        } catch (Exception $e) {
            $productsList = [];
            echo $e->getMessage();
        }
        $products = new ProductCollection();
        foreach ($productsList as $product) {
            $products->add($this->buildModel($product));
        }
        return $products;
    }

    public function getProduct(string $sku): ?Product
    {
        try {
            $product = $this->database->createQueryBuilder()
                ->select('*')
                ->from('products')
                ->where('sku = :sku')
                ->setParameter('sku', $sku)
                ->fetchAssociative();
        } catch (Exception $e) {
            return null;
        }
        if (!$product) {
            return null;
        }
        return $this->buildModel($product);
    }

    public function save(Product $product): void
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
                    'attributename' => ':attributename',
                    'attributevalue' => ':attributevalue'
                ])
                ->setParameters([
                    'sku' => $product->getSku(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'type' => $product->getType(),
                    'attributename' => $product->getAttributeName(),
                    'attributevalue' => $product->getAttributeValue()
                ])
                ->executeQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function delete(Product $product): void
    {
        $sku = $product->getSku();
        $builder = $this->database->createQueryBuilder()
            ->delete('products')
            ->where('sku = :sku')
            ->setParameter('sku', $sku);
        try {
            $builder->executeQuery();
        } catch (Exception $e) {
        }
    }

    public function buildModel(array $data): Product
    {
        $type = 'App\Models\Product'.$data['type'];

        return new $type($data);
    }

    public function createTable()
    {
        try {
            $schema = $this->database->createSchemaManager();
            if ($schema->tablesExist('products')) {
                $schema->dropTable('products');
            }

            $products = new Table('products');
            $products->addColumn('sku', 'string', ['length' => 255, 'notnull' => true]);
            $products->setPrimaryKey(['sku']);
            $products->addUniqueIndex(['sku']);
            $products->addColumn('name', 'string', ['length' => 255]);
            $products->addColumn('price', 'integer');
            $products->addColumn('type', 'string', ['length' => 255]);
            $products->addColumn('attributeName', 'string', ['length' => 255]);
            $products->addColumn('attributeValue', 'string', ['length' => 255]);
            $schema->createTable($products);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}