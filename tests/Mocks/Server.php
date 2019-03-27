<?php

namespace Quickest\Tests\Mocks;

class Server
{
    /**
     * Create mock environment
     *
     * @param  array $userData Array of custom environment keys and values
     *
     * @return self
     */
    public static function mock(array $userData = [])
    {
        // Validates if default protocol is HTTPS to set default port 443
        if ((isset($userData['HTTPS']) && $userData['HTTPS'] !== 'off') ||
            ((isset($userData['REQUEST_SCHEME']) && $userData['REQUEST_SCHEME'] === 'https'))) {
            $defscheme = 'https';
            $defport = 443;
        } else {
            $defscheme = 'http';
            $defport = 80;
        }

        $server = array_merge([
            'SERVER_NAME' => 'localhost',
            'SERVER_ADDR' => '::1',
            'SERVER_PORT' => '8383',
            'REMOTE_ADDR' => '127.0.0.1',
            'REQUEST_SCHEME' => $defscheme,
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_PORT' => $defport,
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => '',
            'REQUEST_URI' => '',
            'SCRIPT_NAME' => '',
            'PATH_INFO' => '',
            'HTTP_HOST' => 'localhost',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
            'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
            'REQUEST_TIME_FLOAT' => microtime(true),
            'REQUEST_TIME' => time(),
        ], $userData);

        return $server;
    }
}
