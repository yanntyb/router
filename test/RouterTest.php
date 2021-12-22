<?php

namespace Yanntyb\Router\Test;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use Yanntyb\Router\Route;
use Yanntyb\Router\RouteAlreadyExisteException;
use Yanntyb\Router\RouteNotFoundException;
use Yanntyb\Router\Router;
use Yanntyb\Router\Test\Feature\FooController;
use Yanntyb\Router\Test\Feature\HomeController;

class RouterTest extends TestCase
{
    /**
     * @return void
     * @throws RouteAlreadyExisteException
     * @throws RouteNotFoundException
     * @throws ReflectionException
     */
    public function test(){
        $router = new Router();

        $routeHome = new Route("home", "/", [HomeController::class, "index"]);
        $router->addRoute($routeHome);
        $this->assertEquals($routeHome, $router->getRoute("home"));
        $this->assertEquals($routeHome, $router->matchPath("/"));
        $this->assertEquals("hello world", $router->matchPath("/")->call("/article/12/slug"));


        $routeArticle = new Route("Article", "/article/{id}/{slug}", function(string $id, string $slug){return "$id : $slug";});
        $router->addRoute($routeArticle);
        $this->assertEquals($routeArticle, $router->matchPath("/article/12/test"));
        $this->assertEquals("12 : slug", $router->matchPath("/article/12/slug")->call("/article/12/slug"));

        $routeBar = new Route("bar", "/foo/{bar}", [FooController::class, "foo"]);
        $router->addRoute($routeBar);
        $this->assertEquals("12", $router->matchPath("/foo/12")->call("/foo/12"));

    }

    public function testIfRouteNotFoundByName(){
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->getRoute("fail");
    }

    public function testIfRouteNotFoundByMatch(){
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->matchPath("/");
    }

    /**
     * @throws RouteAlreadyExisteException
     */
    public function testAlreadyExist(){
        $router = new Router();

        $route = new Route("home", "/", function(){echo "hello world";});
        $router->addRoute($route);

        $this->expectException(RouteAlreadyExisteException::class);
        $router->addRoute($route);
    }
}