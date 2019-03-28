<?php

namespace Quickest\Tests;

use PHPUnit\Framework\TestCase;
use Quickest\Router\Request;
use Quickest\Router\Route;
use Quickest\Router\Router;
use Quickest\Tests\Mocks\Server;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    private $router;

    private $requests = [];

    private $initialServer;

    public function setUp()
    {
        $this->router = new Router(new Request);
        $this->router->add('GET', '/categories', 'App\\Controllers\\CategoriesController@all');
        $this->router->add('GET', '/posts', 'App\\Controllers\\PostsController@all');
        $this->router->add('GET', '/posts/:id', 'App\\Controllers\\PostsController@single', ['id' => '[\d]{1,8}']);
        $this->router->add('GET', '/products', 'App\\Controllers\\ProductsController@all');

        $this->initialServer = $_SERVER;

        $this->requests = [
            Server::mock([
                'PATH_INFO' => '/posts',
                'REQUEST_URI' => '/myapp/posts/all',
                'SCRIPT_NAME' => '/myapp/index.php',
            ]),
            Server::mock([
                'PATH_INFO' => '/posts/list',
                'REQUEST_URI' => '/myapp/posts/list',
                'SCRIPT_NAME' => '/myapp/index.php',
            ]),
            Server::mock([
                'PATH_INFO' => '/posts/123',
                'REQUEST_URI' => '/myapp/posts/123',
                'SCRIPT_NAME' => '/myapp/index.php',
            ])
        ];
    }

    public function testCanAddRouteToRoutesCollection()
    {
        $routes = $this->router->getRoutes()['GET'];

        $this->assertCount(4, $routes);
        $this->assertInstanceOf(Route::class, $routes[0]);
        $this->assertInstanceOf(Route::class, $routes[1]);
        $this->assertInstanceOf(Route::class, $routes[2]);
        $this->assertInstanceOf(Route::class, $routes[3]);
    }

    public function testRouterObjectHasVariableRequestInstanceOfRequestObject()
    {
        $this->assertInstanceOf(Request::class, $this->router->getRequest());
    }

    public function testItCanLoopThroughRoutesAndFindAMatchingToUriRequest()
    {
        $_SERVER = array_merge($this->initialServer, $this->requests[0]);
        $this->assertTrue($this->router->dispatch());

        $_SERVER = array_merge($this->initialServer, $this->requests[1]);
        $this->assertFalse($this->router->dispatch());

        $_SERVER = array_merge($this->initialServer, $this->requests[2]);
        $this->assertTrue($this->router->dispatch());
    }
}
