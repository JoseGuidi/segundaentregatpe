<?php
require_once 'libs/Router.php';
require_once 'app/controllers/character-api.controller.php';

$router = new Router();

$router->addRoute('characters','GET','CharacterApiController','get');
$router->addRoute('characters/:ID','GET','CharacterApiController','get');
$router->addRoute('characters/:ID','DELETE','CharacterApiController','delete');
$router->addRoute('characters','POST','CharacterApiController','add');
$router->addRoute('characters/:ID','PUT','CharacterApiController','edit');


$router->addRoute('characters/pagination/:limit/:page','GET','CharacterApiController','getWithPagination');

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);




