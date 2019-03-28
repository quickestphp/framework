<?php

namespace Quickest\Router;

use Quickest\Router\Request;
use Quickest\Router\Route;

/**
 * This class is responsable for storing Route objects into collection of routes,
 * it will loop through all stored routes and execute them, if no matches
 * found render a 404.
 */
class Router
{
    /** @var Quickest\Router\Request */
    private $request;

    /** @var array */
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    /** @var array */
    private $candidateRoutes = [];

    /** @var Quickest\Router\Route */
    private $matchedRoute;

    /**
     * Constructor
     *
     * @param Quickest\Router\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest():Request
    {
        return $this->request;
    }

    public function add(string $method, string $pattern, $callable, array $conditions = [])
    {
        $method = strtoupper($method);
        $route = new Route($pattern, $callable, $conditions);

        $this->routes[$method][] = $route;
    }

    public function getRoutes():array
    {
        return $this->routes;
    }

    public function dispatch():bool
    {
        $requestUri = $this->request->getUri();
        $method = $this->request->getMethod();

        foreach ($this->routes[$method] as $route) {
            if ($route->matches($requestUri)) {
                $this->candidateRoutes[] = $route;
            }
        }

        if (count($this->candidateRoutes) > 0) {
            $this->dispatchCandidateRoutes($requestUri);

            return true;
        }

        return false;
    }

    private function normalizeSplit(string $delimiter, string $subject):array
    {
        $parts = array_filter(explode($delimiter, $subject));
        $parts = array_values($parts);
        return $parts;
    }

    /**
     * Divide the uri and the patterns by slash, loop through the pattern parts
     * excluding the variables e.g.: (:id, :comment_id) then check if the
     * pattern index exists in the uri array end if the contents of this
     * position matches with the pattern content at the same position
     *
     * @param string $requestUri
     */
    private function dispatchCandidateRoutes(string $requestUri)
    {
        $uriParts = $this->normalizeSplit('/', $requestUri);
        $similars = [];

        foreach ($this->candidateRoutes as $routeIdx => $route) {
            $pattern = $route->getPattern();
            $patternParts = $this->normalizeSplit('/', $pattern);

            foreach ($patternParts as $patternIdx => $pattern) {
                // Check if it is a variable part of the pattern
                if (preg_match('/[\:]/i', $pattern)) {
                    continue;
                }
                
                // Initialize similars routes on a candidate position
                if (!isset($similars[$routeIdx])) {
                    $similars[$routeIdx] = 0;
                }

                // Check uri parts position against pattern index and pattern contents
                if (isset($uriParts[$patternIdx]) && $uriParts[$patternIdx] === $pattern) {
                    $similars[$routeIdx] += 1;
                }
            }
        }

        $bigger = max($similars);
        $mostSimilar = array_search($bigger, $similars);
        $this->matchedRoute = $this->candidateRoutes[$mostSimilar];
        $this->candidateRoutes = [];
    }
}
