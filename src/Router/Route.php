<?php

namespace Quickest\Router;

class Route
{
    /** @var string */
    private $pattern;

    /** @var string|Closure */
    private $callable;

    /** @var array */
    private $conditions = [];

    /** @var array */
    private $params = [];

    public function __construct(string $pattern, $callable, array $conditions = [])
    {
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->conditions = $conditions;
    }

    public function getPattern():string
    {
        return $this->pattern;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    public function getParamsNames():array
    {
        preg_match_all('@:([\w]+)@', $this->pattern, $paramsNames, PREG_PATTERN_ORDER);

        return $paramsNames[0];
    }

    public function getParams():array
    {
        return $this->params;
    }

    public function matches(string $uri):bool
    {
        $uri = $this->appendLastSlash($uri);
        $pattern = $this->appendLastSlash($this->getPattern());
        $callable = $this->getCallable();
        $paramsNames = $this->getParamsNames();

        if ($uri === $pattern) {
            return true;
        } else {
            $regex = preg_replace_callback('@:[\w]+@', [$this, 'convertToRegex'], $pattern);
            $regex = '@^' . $regex . '?$@';

            if (preg_match($regex, $uri, $paramsValues)) {
                array_shift($paramsValues);

                if (count($paramsValues) > 0) {
                    foreach ($paramsNames as $pos => $var) {
                        $this->params[substr($var, 1)] = urldecode($paramsValues[$pos]);
                    }
                }

                return true;
            } else {
                return false;
            }
        }
    }

    private function convertToRegex(array $matches):string
    {
        $key = str_replace(':', '', $matches[0]);

        if (array_key_exists($key, $this->conditions)) {
            return "({$this->conditions[$key]})";
        } else {
            return '([a-zA-Z0-9_\-\.]+)';
        }
    }

    private function appendLastSlash(string $path):string
    {
        $path .= substr($path, -1) !== '/' ? '/' : '';

        return $path;
    }
}
