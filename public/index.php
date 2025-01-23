<?php
require_once '../vendor/autoload.php';

use App\Services\DatabaseSwitcherService;
use App\Controller\UserControllerApi;
use App\Router\Router;

$db = new DatabaseSwitcherService();
$api = new UserControllerApi($db->getDb());

header('Content-type: application/json');

$rout = new Router();
$rout->addRoute('GET', '/list-users', [$api, 'list']);
$rout->addRoute('DELETE', '/delete-user/{id}', [$api, 'delete']);
$rout->addRoute('POST', '/create-user', [$api, 'add']);
$rout->run();