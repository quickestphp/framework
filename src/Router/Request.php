<?php

namespace Quickest\Router;

class Request
{
    public function getUri()
    {
        if (!empty($_SERVER['ORIG_PATH_INFO'])) {
            $uriPath = $_SERVER['ORIG_PATH_INFO'];
        } elseif (!empty($_SERVER['PATH_INFO'])) {
            $uriPath = $_SERVER['PATH_INFO'];
        }

        // some servers have the index.php file into the path, so we remove it
        if (!empty($uriPath)) {
            $uriPath = str_replace('/index.php', '', $uriPath);
        }

        $path = empty($uriPath) ? '/' : strip_tags(trim($uriPath));

        return $path;
    }
}
