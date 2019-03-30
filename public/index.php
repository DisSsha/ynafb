<?php

require "../vendor/autoload.php";

use App\Models\User;
use App\Controllers\PagesController;

session_start();

$app = new \Slim\App([
   'settings' => [
       'displayErrorDetails' => true
   ]
]);

require('../app/container.php');

$container = $app->getContainer();

// Middleware
$app->add(new \App\Middlewares\FlashMiddleware($container->view->getEnvironment()));

// Pages
$app->get('/', PagesController::class . ':home')->setName('root');
$app->get('/budget', PagesController::class . ':getContact')->setName('budget');
$app->get('/transactions', PagesController::class . ':getContact')->setName('transactions');

//$app->post('/nous-contacter', PagesController::class . ':postContact');

/**$app->get('/',function(\Slim\Http\Request $request,\Slim\Http\Response $response) {
    $user = new User();
    $condition = array('username' => "John"); 
    $req = array
            (
            'fields'     => 'user.username as name',
            'order'      => 'name DESC',
            'conditions' => $condition,
            );
    $user = $user->findfirst($req);
    return $response->getBody()->write('Salut '.$user->name);
    });
*/
$app->run();