<?php

namespace App\Views;

use App\Views\View;

class Home extends View
{
    public static function index($view, $layout = null,$request = null)
    {

      
        return parent::render($layout, [
            "page" => parent::render($view)
        ]);
    }
}
