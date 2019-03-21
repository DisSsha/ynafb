<?php

require "../vendor/autoload.php";

use App\Models\User;

$app = new \Slim\App([
   'settings' => [
       'displayErrorDetails' => true
   ]
]);
$app->get('/',function(\Slim\Http\Request $request,\Slim\Http\Response $response) {
    $user = new User();
    $user->
    return $response->getBody()->write('Salut '.$user->findfirst(array('field')));
});
$app->run();