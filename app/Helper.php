<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ProductController;

class Helper
{
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

    public static function test()
    {
    }

}

foreach ($argv as $arg) {
    method_exists(Helper::class, $arg) and call_user_func([Helper::class, $arg]);
}