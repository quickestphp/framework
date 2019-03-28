<?php

namespace Unit\Tests;

use PHPUnit\Framework\TestCase;
use Quickest\Router\Request;
use Quickest\Tests\Mocks\Server;

class RequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        $this->request = new Request();

        // Mock request to http://localhost/posts
        $server = Server::mock([
            'PATH_INFO' => '/posts/list',
            'REQUEST_URI' => '/myapp/posts/list',
            'SCRIPT_NAME' => '/myapp/index.php',
        ]);

        // Override the global $_SERVER
        $_SERVER = array_merge($_SERVER, $server);
    }

    public function testCanGetCurrentRequestUri()
    {
        $this->assertEquals('/posts/list', $this->request->getUri());
    }

    public function testCanGetCorrectRequestMethodType()
    {
        $serverRequest01 = Server::mock([
            'PATH_INFO' => '/posts',
            'REQUEST_URI' => '/myapp/posts',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/myapp/index.php',
        ]);

        $serverRequest02 = Server::mock([
            'PATH_INFO' => '/posts',
            'REQUEST_URI' => '/myapp/posts',
            'REQUEST_METHOD' => 'POST',
            'SCRIPT_NAME' => '/myapp/index.php',
        ]);

        $serverRequest03 = Server::mock([
            'PATH_INFO' => '/posts/12',
            'REQUEST_URI' => '/myapp/posts/12',
            'REQUEST_METHOD' => 'PUT',
            'SCRIPT_NAME' => '/myapp/index.php',
        ]);

        $serverRequest04 = Server::mock([
            'PATH_INFO' => '/posts/12',
            'REQUEST_URI' => '/myapp/posts/12',
            'REQUEST_METHOD' => 'PATCH',
            'SCRIPT_NAME' => '/myapp/index.php',
        ]);

        $serverRequest05 = Server::mock([
            'PATH_INFO' => '/posts/12',
            'REQUEST_URI' => '/myapp/posts/12',
            'REQUEST_METHOD' => 'DELETE',
            'SCRIPT_NAME' => '/myapp/index.php',
        ]);
        
        $initialServer = $_SERVER;

        $_SERVER = array_merge($initialServer, $serverRequest01);
        $this->assertEquals('GET', $this->request->getMethod());

        $_SERVER = array_merge($initialServer, $serverRequest02);
        $this->assertEquals('POST', $this->request->getMethod());

        $_SERVER = array_merge($initialServer, $serverRequest03);
        $this->assertEquals('PUT', $this->request->getMethod());

        $_SERVER = array_merge($initialServer, $serverRequest04);
        $this->assertEquals('PATCH', $this->request->getMethod());

        $_SERVER = array_merge($initialServer, $serverRequest05);
        $this->assertEquals('DELETE', $this->request->getMethod());
    }
}
