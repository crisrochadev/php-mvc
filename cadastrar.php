<?php

use App\Models\User;

require __DIR__ . "/autoload.php";

$request = $_POST;

echo '<pre>';
print_r($request);
echo '</pre>';


$user = new User();
$res = $user->add($request);
// $res = $user->get();
// $res = $user->find(1);
// $res = $user->update(1,[
//     "firstName" => "Cristianesinha"
// ]);
// $res = $user->delete(3)
echo '<pre>';
print_r($res);
echo '</pre>';
