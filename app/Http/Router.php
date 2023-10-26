<?php

namespace App\Http;

use Closure;

class Router
{
    private string $uri;
    private string $host;
    private array $request = [];
    private string $method;
    private $headers = [];
    private $function;
    private $content;
    private string $path;
    private string $contentType = "text/html";
    public function __construct()
    {

        $this->uri = strpos($_SERVER["REQUEST_URI"], "?") !== FALSE ? explode("?", $_SERVER["REQUEST_URI"], 2)[0] : $_SERVER["REQUEST_URI"];
        $this->host = $_SERVER["HTTP_HOST"];
        $this->method = $_SERVER["REQUEST_METHOD"];
        header("Access-Control-Allow-Headers:Content-type");
        header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE");
        header("Access-Control-Allow-Origin:http://");
        $this->request['body'] = $_POST;
        if (key_exists("QUERY_STRING", $_SERVER)) {
            foreach (explode("&", $_SERVER["QUERY_STRING"]) as $q) {
                list($key, $value) = explode("=", $q, 2);
                $this->request["query"][$key] = $value;
            }
        }
    }

    private function sendHeader()
    {
        foreach ($this->headers as $value) {
            header($value);
        }
    }
    private function setHeaders($header)
    {
        foreach ($header as $key => $value) {
            $item = $key . ":" . $value;
            $pos = array_search($key . ":" . $value, $this->headers);
            if ($pos !== false) {
                $this->headers[$pos] = $item;
            } else {
                array_push($this->headers, $item);
            }
        }
    }
    private function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->setHeaders(["Content-Type" => $this->contentType]);
    }
    private function checkUri($path)
    {


        if (count(explode("/", $this->uri)) === count(explode("/", $path))) {
            preg_match_all('/\{([^}]+)\}/', $path, $matches);
            $placeholders = $matches[1];


            // Obtenha os valores correspondentes de $string2
            $values = explode('/', trim($this->uri, '/'));

            foreach ($placeholders as $index => $placeholder) {
                if (isset($values[$index])) {
                    if ($values[$index] !== $placeholder) {
                        return false; // Os valores não correspondem
                    }
                } else {
                    return false; // Não há valor correspondente em $this->uri
                }
            }
            // Substitua os placeholders pelos valores correspondentes
            foreach ($placeholders as $index => $placeholder) {
                $this->request['params'][$placeholder] = $values[$index];

                if (isset($values[$index])) {
                    $path = str_replace("{" . $placeholder . "}", $values[$index], $path);
                }
            }
        }
        return $path;
    }
    public function get($path, $content, $vars, $contentType = "text/html")
    {
        $path = $this->checkUri($path);



        if ($path !== $this->uri) {
            return;
        } else {

            $this->setContentType($contentType);
            $this->request['vars'] = $vars;
            $this->content = $content;
            $this->sendResponse();
        }
    }

    public function post($path, $content, $vars = [], $contentType = "text/html")
    {
        $path = $this->checkUri($path);
       
        if ($path === $this->uri) {
            if ($this->method !== "POST") {
                $this->content = fn () => "Methodo não permitido";
                $this->sendResponse();
                return;
            } else {
                $this->setContentType($contentType);
                $this->request['vars'] = $vars;
                $this->content = $content;
                $this->sendResponse();
            }
        }
    }
    private function sendResponse()
    {
        $this->sendHeader();
        switch ($this->contentType) {
            case 'text/html':
                echo call_user_func($this->content, $this->request);
                break;
            case 'application/json':
                die(json_encode(call_user_func($this->content, $this->request)));
                break;
        }
    }
}
