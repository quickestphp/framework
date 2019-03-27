<?php

namespace Unit\Tests;

use PHPUnit\Framework\TestCase;
use Quickest\Router\Request;
use Quickest\Tests\Mocks\Server;

class RequestTest extends TestCase
{
    public function setUp()
    {
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
        $request = new Request();

        $this->assertEquals('/posts/list', $request->getUri());
    }
}
