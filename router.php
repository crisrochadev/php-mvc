<?php

use App\Controller\UserController;
use App\Http\Router;
use App\Views\Home;

$router = new Router;



$router->get("/", function () {
    return Home::index('pages/home', 'layouts/public');
}, ["name" => "Cris"]);

$router->get("/{id}", function ($request) {
    return Home::index('pages/home', 'layouts/public',$request);
}, ["name" => "Cris"]);

$router->post("/register", function ($request) {
    return UserController::index($request);
}, [], "application/json");

