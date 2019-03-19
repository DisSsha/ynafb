<?php

require "../vendor/autoload.php";
//require "../Models/User.php";
use App\Models\User;

$app = new \Slim\App([
   'settings' => [
       'displayErrorDetails' => true
   ]
]);
$app->get('/',function(\Slim\Http\Request $request,\Slim\Http\Response $response) {
    $user = new User("jean");
    return $response->getBody()->write('Salut '.$user->getNom());
});
$app->run();