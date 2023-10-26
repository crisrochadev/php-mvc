<?php

namespace App\Models;

use App\Models\Model;
use Exception;

class User extends Model
{
    private string $email;
    private string $username;
    private string $firstName;
    private string $lastName;
    private string $password;
    private string $picture;

    public function __construct()
    {
        parent::__construct("user");
    }
    public function add($data)
    {
        try {
            if (!key_exists("email", $data) || !key_exists("username", $data) || !key_exists("password", $data)) {
                throw new Exception("Os campos email, username e password são obrigatório");
            }
            if ($data["email"] === "" || $data["username"] === "" || $data["password"] === "") {
                throw new Exception("Os campos email, username e password são obrigatório");
            }
            $this->email = key_exists("email", $data) ? $data["email"] : "";
            $this->username = key_exists("username", $data) ? $data["username"] : "";
            $this->firstName = key_exists("fisrtName", $data) ? $data["firstName"] : "";
            $this->lastName = key_exists("lastName", $data) ? $data["lastName"] : "";
            $this->password = key_exists("password", $data) ? $data["password"] : "";
            $this->picture = key_exists("picture", $data) ? $data["picture"] : "";




            parent::insert([
                "username" => $this->username,
                "email" => $this->email,
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "password" => $this->password,
                "picture" => $this->picture,
            ]);
            parent::save();

            return $this->response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function get()
    {
        parent::getAll();
        return $this->response;
    }
    public function find($id)
    {
        parent::getById($id);
        return $this->response;
    }
    public function update($id, $data)
    {
        parent::put($id, $data);
        parent::save();
        return $this->response;
    }
    public function delete($id)
    {
        parent::del($id);
        parent::save();
        return $this->response;
    }
}
