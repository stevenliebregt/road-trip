<?php

/*
 * This file is part of Road Trip.
 *
 * (c) 2018 Steven Liebregt <stevenliebregt@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace StevenLiebregt\RoadTrip;

use StevenLiebregt\RoadTrip\Exceptions\FileNotFoundException;

class Router
{
    const CACHE_FILE = '%s.cache';

    const NOT_FOUND = 0;
    const FOUND = 1;

    /** @var bool Defines if we should cache the compiled routes. */
    private $shouldCache = false;

    /** @var array Holds the directories that contain the route files. */
    private $routeDirs = [];

    /** @var string Holds the directory in which the cached routes get saved. */
    private $cacheDir = 'cache';

    /** @var array Holds the collections of routes added to this router instance.  */
    private $collections = [];

    /** @var array Holds the compiled routes. */
    private $compiledRoutes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
        'HEAD' => [],
    ];

    /** @var array Holds a possible match. */
    private $match = [
        'result' => Router::NOT_FOUND,
        'route' => null,
    ];

    /**
     * Set the route directories.
     *
     * @param array $dirs The directories to use as route containing directories.
     *
     * @return Router This router instance to allow for method chaining.
     */
    public function setRouteDirs(array $dirs): Router
    {
        $this->routeDirs = $dirs;

        return $this;
    }

    /**
     * Add route directories to an existing list.
     *
     * @param array $dirs The directories to use as route containing directories.
     *
     * @return Router This router instance to allow for method chaining.
     */
    public function addRouteDirs(array $dirs): Router
    {
        $this->routeDirs = array_merge($this->routeDirs, $dirs);

        return $this;
    }

    /**
     * Set if the router should use cache or not.
     *
     * @param bool $shouldCache Should we cache or not.
     *
     * @return Router This router instance to allow for method chaining.
     */
    public function shouldCache(bool $shouldCache): Router
    {
        $this->shouldCache = $shouldCache;

        return $this;
    }

    /**
     * Set the directory in which the route cache gets saved.
     *
     * @param string $dir The directory in which the route cache gets saved.
     *
     * @return Router This router instance to allow for method chaining.
     */
    public function setCacheDir(string $dir): Router
    {
        $this->cacheDir = $dir;

        return $this;
    }

    /**
     * Checks if the route cache folder exists and is not empty.
     *
     * @return bool If the cache folder exists and is not empty.
     */
    public function hasCache(): bool
    {
        if (file_exists($this->cacheDir) &&
            is_readable($this->cacheDir) &&
            count(scandir($this->cacheDir)) > 2) {

            return true;
        }

        return false;
    }

    /**
     * Add a given collection to this router instance.
     *
     * @param RouteCollection $collection The collection to add.
     *
     * @return Router This instance to allow for method chaining.
     */
    public function addCollection(RouteCollection $collection): Router
    {
        $this->collections[] = $collection;

        return $this;
    }

    /**
     * Add a collection from the given file to this router instance.
     *
     * @param string $file The file in which the collection resides.
     *
     * @throws FileNotFoundException Throws a FileNotFoundException when the given filename did not lead to an
     * existing file.
     *
     * @return Router This instance to allow for method chaining.
     */
    public function addCollectionFromFile(string $file): Router
    {
        $collection = null;

        // Check if file exists here.
        if (!file_exists($file)) {
            // Check if it perhaps is found with .php extension.
            if (!file_exists($file . '.php')) {
                // Loop through route dirs.
                foreach ($this->routeDirs as $dir) {
                    // Check if file exists.
                    if (!file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
                        // Check if it perhaps is found with a .php extension.
                        if (file_exists($dir . DIRECTORY_SEPARATOR . $file . '.php')) {
                            $collection = require $dir . DIRECTORY_SEPARATOR . $file . '.php';
                        }
                    } else {
                        $collection = require $dir . DIRECTORY_SEPARATOR . $file;
                    }

                    // Check if we have found it so we can stop looping.
                    if ($collection !== null) {
                        continue;
                    }
                }
            } else {
                $collection = require $file . '.php';
            }
        } else {
            $collection = require $file;
        }

        if ($collection === null) {
            throw new FileNotFoundException('The requested route file: [ ' . $file . ' ] could not be found');
        }

        $this->addCollection($collection);

        return $this;
    }

    /**
     * Compile the collections that are currently added.
     */
    public function compile(): void
    {
        /** @var RouteCollection $collection */
        foreach ($this->collections as $collection) {
            foreach ($collection->getRoutes() as $method => $routes) {
                /** @var Route $route */
                foreach ($routes as $route) {
                    $this->compiledRoutes[$method][] = $route->compile();
                }
            }
        }

        if ($this->shouldCache) {
            $this->cache();
        }
    }

    /**
     * Caches the currently added compiled routes to the defined cache file.
     */
    private function cache(): void
    {
        mkdir($this->cacheDir);

        foreach ($this->compiledRoutes as $method => $routes) {
            $file = $this->cacheDir . DIRECTORY_SEPARATOR . $method . '.cache';
            file_put_contents($file, serialize($routes));
        }
    }

    /**
     * Checks whether any of the registered routes match the given HTTP method and URI.
     * 
     * @param string $method The HTTP method that is currently used with the request.
     * @param string $uri The currently used URI. This should be without the domain and without the query.
     *
     * @return array Returns a key, value array with the keys 'result' and 'route'. The key 'result' tells you
     * if the request was either Router::FOUND, Router::METHOD_NOT_ALLOWED or Router::NOT_FOUND.
     * The key 'route' gives you the matched route from which you can extract the handler.
     */
    public function match(string $method, string $uri): array
    {
        $routes = $this->shouldCache ? $this->loadRoutesFromFile($method) : $this->compiledRoutes;

        // Check if we can find a match.
        /** @var Route $route */
        foreach ($routes as $route) {
            if ($route->isMatch($uri)) {
                $this->match = [
                    'result' => Router::FOUND,
                    'route' => $route,
                ];

                return $this->match;
            } else {
                $this->match = [
                    'result' => Router::NOT_FOUND,
                ];
            }
        }

        return $this->match;
    }

    /**
     * Load routes for a given HTTP method from the cache file.
     *
     * @param string $method The HTTP method to get routes for.
     *
     * @return array The routes for the asked method.
     */
    private function loadRoutesFromFile(string $method): array
    {
        $file = $this->cacheDir . DIRECTORY_SEPARATOR . sprintf(Router::CACHE_FILE, $method);

        return unserialize(file_get_contents($file));
    }
}
