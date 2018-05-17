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
	const CACHE_FILENAME = 'road-trip.cache.php';

	/** @var array Holds the directories that contain the route files. */
	private $routeDirs = [];

	/** @var string Holds the directory in which the cached routes get saved. */
	private $cacheDir = '';

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
		// TODO: implement

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

		// TODO: do something with collection maybe.

		return $this;
	}
}