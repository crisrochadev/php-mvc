<?php

namespace App\Controller;

use App\Repositories\UserRepo;
use App\Views\Home;

class UserController
{


    public static function index($request)
    {
        $repo = new UserRepo();
        $res = $repo->create($request["body"]);

        return $res;
    }
}
