<?php

namespace App\Models;

use PDO;
use PDOException;

class Model
{
    private string $name;
    private $pdo;
    private $stmt;
    private string $sql;
    private array $values = [];
    private array $data;
    public array $response = [
        "success" => TRUE,
        "error" => FALSE,
        "message" => "Dados Inseridos com sucesso",
        "data" => []
    ];
    public function __construct($name)
    {
        $this->name = $name;
        if ($this->pdo === NULL) {
            try {
                $this->pdo = new PDO("mysql:host=localhost;dbname=sweet", "root", "");
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                $this->response["error"] = TRUE;
                $this->response["success"] = FALSE;
                $this->response["error"] =  $e->getMessage();
            } catch (\Exception $e) {
                $this->response["error"] = TRUE;
                $this->response["success"] = FALSE;
                $this->response["error"] =  $e->getMessage();
            }
        }
    }
    public function insert($model, $keys = "email,username,password")
    {
        $keys = implode(",", array_keys($model));
        $refs = implode(",", array_map(function ($val) {
            return "?";
        }, explode(",", $keys)));
        $this->values = array_values($model);
        $this->stmt = $this->pdo->prepare("INSERT INTO $this->name ($keys) VALUES ($refs)");
    }

    public function getAll()
    {

        $this->stmt = $this->pdo->prepare("SELECT * FROM $this->name");
        $this->stmt->execute();
        $this->response = [
            "success" => TRUE,
            "data" => $this->stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }
    public function getById($id)
    {

        $this->stmt = $this->pdo->prepare("SELECT * FROM $this->name WHERE id = $id");
        $this->stmt->execute();
        $this->response = [
            "success" => TRUE,
            "data" => $this->stmt->fetch(PDO::FETCH_ASSOC)
        ];
    }
    public function put($id, $model)
    {
        $refs = "";
        foreach ($model as $key => $value) {
            $refs = $refs . "," . $key . "=?";
        }
        $refs = substr($refs, 1);

        $this->values = array_values($model);
        $this->stmt = $this->pdo->prepare("UPDATE $this->name SET $refs WHERE id = $id");
        $this->response["error"] = FALSE;
        $this->response["success"] = TRUE;
        $this->response["error"] =  "Dados alterados com successo";
        $this->response["data"] = ["updated" => count($this->values)];
    }
    public function del($id)
    {

        $this->getById($id);
        if (!$this->response["data"]) {
            $this->response["error"] = TRUE;
            $this->response["success"] = FALSE;
            $this->response["error"] =  "Dado não encontrado";
        } else {
            $this->stmt = $this->pdo->prepare("DELETE FROM $this->name  WHERE id = $id");
            $this->response["error"] = FALSE;
            $this->response["success"] = TRUE;
            $this->response["error"] =  "Dado deletado com successo";
            $this->response["data"] = ["deleted" => 1];
        }
    }


    public function save()
    {
        try {
            $this->stmt->execute($this->values);
            $error = $this->stmt->errorInfo();

            if ($error[2]) {
                throw new PDOException($error[2]);
            }
        } catch (\PDOException $e) {
            $this->response["error"] = TRUE;
            $this->response["success"] = FALSE;
            $this->response["error"] =  $e->getMessage();
        } catch (\Exception $e) {
            $this->response["error"] = TRUE;
            $this->response["success"] = FALSE;
            $this->response["error"] =  $e->getMessage();
        }
    }
}
