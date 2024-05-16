<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ProductController;
use App\Models\Product;
use App\Repositories\MySqlRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Table;

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
        $this->repository->save(
            new Product(
                '123', 'matrix', 100, 'DVD', 'size', '10mb'
            )
        );
        
        $this->repository->save(
            new Product(
                '456', 'war in peaces', 200, 'Book', 'color', 'red'
            )
        );
        $this->repository->save(
            new Product(
                '789', 'shelf', 300, 'Furniture', 'dimensions', '10x20x10'
            )
        );
        $this->repository->save(
            new Product('012', 'table', 400, 'Furniture', 'dimensions', '10x10x10')
        );
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