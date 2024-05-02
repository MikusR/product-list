<?php

namespace App;

class Helper
{
    public static function info()
    {
        echo "<pre>";
        echo phpinfo();
    }

    public static function dump($value)
    {
        echo '<pre>';
        var_dump($value);
    }

    public function seed()
    {
    }
}