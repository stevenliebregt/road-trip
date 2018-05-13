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

class RouteCollection
{
	/**
	 * @var array $routes Holds the list of all routes, grouped as a multidimensional array where the key is the
	 * HTTP request method associated.
	 */
	private $routes = [
		'GET' => [],
		'POST' => [],
		'PUT' => [],
		'PATCH' => [],
		'DELETE' => [],
		'HEAD' => [],
	];

	/**
	 * @var string|null $pathPrefix Holds the prefix for the path to match.
	 */
	private $pathPrefix = null;

	/**
	 * @var string|null $handlerPrefix Holds the prefix for the handler to execute.
	 */
	private $handlerPrefix = null;

	/**
	 * Set the options for this collection instance with a key value array.
	 *
	 * The following options are special, and can be used to set those options in one array:
	 *  - pathPrefix, this will call the RouteCollection::setPathPrefix method with the given value.
	 *  - handlerPrefix, this will call the RouteCollection::setHandlerPrefix method with the given value.
	 *
	 * Every given option that is not defined above, will be set in the $miscOptions property. These can later be
	 * retrieved for custom handling.
	 *
	 * Keys that have a null value, clear any previously set value for that option.
	 *
	 * @param array $options The key value array with the options to set.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setOptions(array $options): RouteCollection
	{
		// Check if the path prefix is set in the options array.
		if (array_key_exists('pathPrefix', $options))
		{
			$this->setPathPrefix($options['pathPrefix']);
		}

		// Check if the handler prefix is set in the options array.
		if (array_key_exists('handlerPrefix', $options))
		{
			$this->setHandlerPrefix($options['handlerPrefix']);
		}

		// TODO: Loop through options and set them, look for null values.

		return $this;
	}

	/**
	 * Set the path prefix for this collection instance.
	 *
	 * @param string $pathPrefix The new path prefix.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setPathPrefix(string $pathPrefix): RouteCollection
	{
		$this->pathPrefix = $pathPrefix;

		return $this;
	}

	/**
	 * Set the handler prefix for this collection instance.
	 *
	 * @param string $handlerPrefix The new handler prefix.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setHandlerPrefix(string $handlerPrefix): RouteCollection
	{
		$this->handlerPrefix = $handlerPrefix;

		return $this;
	}
}