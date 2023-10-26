<?php

namespace App\Repositories;

use App\Models\User;

class UserRepo 
{
    private $user;
    public function __construct()
    {
        $this->user = new User();
    }
    public function create($data)
    {
        $result = $this->user->add($data);
        return $result;
    }
}
