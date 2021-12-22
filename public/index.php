<?php

use Yanntyb\Router\Controller\FooController;
use Yanntyb\Router\Controller\HomeController;
use Yanntyb\Router\Model\Classes\Route;
use Yanntyb\Router\Model\Classes\Router;

require "../vendor/autoload.php";

$defaultRoute = new Route("home", "/home",[HomeController::class,"index"]);
$router = new Router($defaultRoute);

$router->addRoute("foo","/echo/{message}",[FooController::class, "displayMessage"]);

$query = str_replace("/index.php","",$_SERVER['PHP_SELF']);

$router->handleQuery($query);