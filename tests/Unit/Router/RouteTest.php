<?php

namespace Unit\Tests;

use Quickest\Router\Route;

class RouteTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructorCanReceiveNecessaryArguments()
    {
        $pattern = '/home';
        $callable = 'App\\Controllers\\HomeController@index';
        $route = new Route($pattern, $callable);

        $this->assertEquals($pattern, $route->getPattern());
        $this->assertEquals($callable, $route->getCallable());
    }

    public function testCanGetVariablesFromPattern()
    {
        $route = new Route('/products/list/:id/:comment_id', 'App\\Controllers\\ProductsController@list');
        $route->matches('/products/list/12/32');

        $this->assertCount(2, $route->getParamsNames());
        $this->assertEquals(':id', $route->getParamsNames()[0]);
        $this->assertEquals(':comment_id', $route->getParamsNames()[1]);

        $this->assertCount(2, $route->getParams());
        $this->assertArrayHasKey('id', $route->getParams());
        $this->assertArrayHasKey('comment_id', $route->getParams());
    }

    public function testCanMatchRequestByPattern()
    {
        // Emulate a collection of routes
        $routes[] = new Route('/products/list', 'App\\Controllers\\ProductsController@list');
        $routes[] = new Route('/products/list/:id/:comment_id', 'App\\Controllers\\ProductsController@list');

        $this->assertTrue($routes[0]->matches('/products/list'));
        $this->assertTrue($routes[1]->matches('/products/list/12/32'));
        $this->assertFalse($routes[1]->matches('/categories/11/edit'));
        $this->assertFalse($routes[1]->matches('/categories/12/delete'));
    }
}
