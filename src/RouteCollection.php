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
	 * @var string|null $pathPrefix Holds the prefix for the path to match.
	 */
	private $pathPrefix = null;

	/**
	 * @var string|null $handlerPrefix Holds the prefix for the handler to execute.
	 */
	private $handlerPrefix = null;

	/**
	 * @var array $miscOptions Holds the custom options that the user has set with the RouteCollection::setOptions
	 * method.
	 */
	private $miscOptions = [];

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
	 * @var bool $hasChildren Tells us if this collection has child collections.
	 */
	private $hasChildren = false;

	/**
	 * @var array $children Holds the child collections.
	 */
	private $children = [];

	/**
	 * Create a new collection but keep the prefixes and options from this instance.
	 *
	 * @return RouteCollection
	 */
	private function clone(): RouteCollection
	{
		$clone = new RouteCollection();
		$clone->pathPrefix = $this->pathPrefix;
		$clone->handlerPrefix = $this->handlerPrefix;
		$clone->miscOptions = $this->miscOptions;

		return $clone;
	}

	/**
	 * Set the options for this collection instance with a key value array.
	 *
	 * @see RouteCollection::setOption().
	 *
	 * @param array $options The key value array with the options to set.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setOptions(array $options): RouteCollection
	{
		// Loop through extra options.
		foreach ($options as $key => $value)
		{
			$this->setOption($key, $value);
		}

		return $this;
	}

	/**
	 * Return all options from this collection.
	 *
	 * @return array All options.
	 */
	public function getOptions(): array
	{
		$options = [
			'pathPrefix' => $this->pathPrefix,
			'handlerPrefix' => $this->handlerPrefix,
		];

		return array_merge($options, $this->miscOptions);
	}

	/**
	 * Set an option by giving key and value.
	 *
	 * The following options are special, and can be used to set those options in one array:
	 *  - pathPrefix, this will call the RouteCollection::setPathPrefix method with the given value.
	 *  - handlerPrefix, this will call the RouteCollection::setHandlerPrefix method with the given value.
	 *
	 * Every given option that is not defined above, will be set in the $miscOptions property. These can later be
	 * retrieved for custom handling.
	 *
	 * @param string $key The key of the option.
	 * @param mixed $value The new value of the option, this can be anything, even null.
	 *
	 * @return RouteCollection This instance to allow for chaining methods.
	 */
	public function setOption(string $key, $value): RouteCollection
	{
		// Check if the key equals pathPrefix.
		if ($key === 'pathPrefix')
		{
			$this->setPathPrefix($value);

			return $this;
		}

		// Check if the key equals handlerPrefix.
		if ($key === 'handlerPrefix')
		{
			$this->setHandlerPrefix($value);

			return $this;
		}

		// Set any other option.
		$this->miscOptions[$key] = $value;

		return $this;
	}

	/**
	 * Return the value of an option.
	 *
	 * @param string $key The key of the option.
	 *
	 * @return mixed|null|string The value of the wanted key.
	 */
	public function getOption(string $key)
	{
		// Check if the key equals pathPrefix.
		if ($key === 'pathPrefix')
		{
			return $this->pathPrefix;
		}

		// Check if the key equals handlerPrefix.
		if ($key === 'handlerPrefix')
		{
			return $this->handlerPrefix;
		}

		// Check if key is set.
		if (!isset($this->miscOptions[$key]))
		{
			return null;
		}

		return $this->miscOptions[$key];
	}

	/**
	 * Remove the value of the given key in the $miscOptions property. If the name is either pathPrefix or handlerPrefix
	 * those values will get set to null.
	 *
	 * @param string $key The key of the value to unset.
	 *
	 * @return RouteCollection This instance to allow method chaining.
	 */
	public function removeOption(string $key): RouteCollection
	{
		// Check if key equals pathPrefix.
		if ($key === 'pathPrefix')
		{
			$this->pathPrefix = null;

			return $this;
		}

		// Check if key equals handlerPrefix.
		if ($key === 'handlerPrefix')
		{
			$this->handlerPrefix = null;

			return $this;
		}

		// Check for any other options.
		unset($this->miscOptions[$key]);

		return $this;
	}

	/**
	 * Set the path prefix for this collection instance.
	 *
	 * @param string $pathPrefix The new path prefix.
	 * @param bool $append If the path prefix should be appended or not.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setPathPrefix(?string $pathPrefix, bool $append = false): RouteCollection
	{
		$this->pathPrefix = $append ?
			$this->pathPrefix . $pathPrefix :
			$pathPrefix;

		return $this;
	}

	/**
	 * Return the value of the pathPrefix.
	 *
	 * @return null|string The value of pathPrefix.
	 */
	public function getPathPrefix(): ?string
	{
		return $this->pathPrefix;
	}

	/**
	 * Set the handler prefix for this collection instance.
	 *
	 * @param string $handlerPrefix The new handler prefix.
	 * @param bool $append If the handler prefix should be appended or not.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setHandlerPrefix(?string $handlerPrefix, bool $append = false): RouteCollection
	{
		$this->handlerPrefix = $append ?
			$this->handlerPrefix . $handlerPrefix :
			$handlerPrefix;

		return $this;
	}

	/**
	 * Return the value of handlerPrefix.
	 *
	 * @return null|string The value of handlerPrefix.
	 */
	public function getHandlerPrefix(): ?string
	{
		return $this->handlerPrefix;
	}

	/**
	 * Add a new route with the given HTTP method.
	 *
	 * @param string $method The HTTP method to match this route on.
	 * @param string $path The path to match this route on.
	 * @param string|callable $handler The handler for this route, must be either a string that defines a class prefixed
	 * with an optional namespace, with the name of the method to call appended with a dot. Or a closure to call.
	 *
	 * @return Route This instance to allow for method chaining.
	 */
	private function add(string $method, string $path, $handler): Route
	{
		// Create a new Route instance.
		$route = new Route($this, $method, $path, $handler);

		// Add the route to the list.
		$this->routes[$method][] = $route;

		return $route;
	}

	/**
	 * Register a new route for the HTTP GET method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return Route
	 */
	public function get(string $path, $handler): Route
	{
		return $this->add('GET', $path, $handler);
	}

	/**
	 * Register a new route for the HTTP POST method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return Route
	 */
	public function post(string $path, $handler): Route
	{
		return $this->add('POST', $path, $handler);
	}

	/**
	 * Register a new route for the HTTP PUT method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return Route
	 */
	public function put(string $path, $handler): Route
	{
		return $this->add('PUT', $path, $handler);
	}

	/**
	 * Register a new route for the HTTP PATCH method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return Route
	 */
	public function patch(string $path, $handler): Route
	{
		return $this->add('PATCH', $path, $handler);
	}

	/**
	 * Register a new route for the HTTP DELETE method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return Route
	 */
	public function delete(string $path, $handler): Route
	{
		return $this->add('DELETE', $path, $handler);
	}

	/**
	 * Register a new route for the HTTP HEAD method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return Route
	 */
	public function head(string $path, $handler): Route
	{
		return $this->add('HEAD', $path, $handler);
	}

	/**
	 * Register new routes for the given HTTP methods.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param array $methods
	 * @param string $path
	 * @param $handler
	 *
	 * @return array
	 */
	public function match(array $methods, string $path, $handler): array
	{
		$routes = [];

		foreach ($methods as $method)
		{
			$routes[strtoupper($method)] = $this->add(strtoupper($method), $path, $handler);
		}

		return $routes;
	}

	/**
	 * Register new routes for the any HTTP method.
	 *
	 * @see RouteCollection::add() for information about the parameters and return values.
	 *
	 * @param string $path
	 * @param $handler
	 *
	 * @return array
	 */
	public function any(string $path, $handler): array
	{
		return [
			'GET' => $this->add('GET', $path, $handler),
			'POST' => $this->add('POST', $path, $handler),
			'PUT' => $this->add('PUT', $path, $handler),
			'PATCH' => $this->add('PATCH', $path, $handler),
			'DELETE' => $this->add('DELETE', $path, $handler),
			'HEAD' => $this->add('HEAD', $path, $handler),
		];
	}

	/**
	 * Create a new collection that is a child of this collection.
	 *
	 * The newly created collection inherits all options.
	 *
	 * @param \Closure $closure A closure in which a new set of routes can be defined.
	 */
	public function group(\Closure $closure)
	{
		$clone = $this->clone();

		$closure($clone);

		$this->hasChildren = true;
		$this->children[] = $clone;
	}
}