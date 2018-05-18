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
	const CACHE_FILENAME = 'road-trip.cache';

	const NOT_FOUND = 0;
	const FOUND = 1;
	const METHOD_NOT_ALLOWED = 2;

    /** @var bool Defines if we should cache the compiled routes. */
    private $shouldCache = false;

	/** @var array Holds the directories that contain the route files. */
	private $routeDirs = [];

	/** @var string Holds the directory in which the cached routes get saved. */
	private $cacheDir = '';

	/** @var array Holds the collections of routes added to this router instance.  */
	private $collections = [];

	/**
	 * Set the route directories.
	 *
	 * @param array $dirs The directories to use as route containing directories.
	 */
	public function setRouteDirs(array $dirs): void
	{
		$this->routeDirs = $dirs;
	}

	/**
	 * Add route directories to an existing list.
	 *
	 * @param array $dirs The directories to use as route containing directories.
	 */
	public function addRouteDirs(array $dirs): void
	{
		$this->routeDirs = array_merge($this->routeDirs, $dirs);
	}

	/**
	 * Set the directory in which the route cache gets saved.
	 *
	 * @param string $dir The directory in which the route cache gets saved.
	 */
	public function setCacheDir(string $dir): void
	{
		$this->cacheDir = $dir;
	}

	/**
	 * Checks if the route cache file exists.
	 *
	 * @return bool If the cache file exists.
	 */
	public function hasCache(): bool
	{
		$file = ($this->cacheDir === '') ? Router::CACHE_FILENAME : $this->cacheDir . DIRECTORY_SEPARATOR . Router::CACHE_FILENAME;

		return file_exists($file);
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
        // TODO: compile the routes. Add them to this router instance.

        if ($this->shouldCache) {
            // TODO: cache
        }
    }

    /**
     * Caches the currently added compiled routes to the defined cache file.
     */
    private function cache(): void
    {
        // TODO: cache the currently compiled routes
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
        if ($this->shouldCache) {
            // TODO: we should load the compiled routes from the cache file.
        }        
    }
}
