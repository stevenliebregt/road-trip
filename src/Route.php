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

class Route
{
	/**
	 * @var RouteCollection $collection Holds the collection that contains this route.
	 */
	private $collection;

	/**
	 * @var string Holds the HTTP method that this route should match for.
	 */
	private $method;

	/**
	 * @var string Holds the path that this route should be matched on.
	 */
	private $path;

	/**
	 * @var string|callable $handler Holds the handler for this route.
	 */
	private $handler;

	/**
	 * @var array $rules Holds the rules for the parameters in the path.
	 */
	private $rules = [];

	/**
	 * @var string $name Holds the name of this route.
	 */
	private $name;

	public function __construct(RouteCollection $collection, string $method, string $path, $handler)
	{
		$this->collection = $collection;
		$this->method = $method;
		$this->path = $path;
		$this->handler = $handler;

		/*

		TODO: This is the old version, in this version the pathPrefix and handlerPrefix get added directly, but that
		might not be necessary since we also set the parent RouteCollection item which contains these variables.

		$this->collection = $collection;
		$this->method = $method;
		$this->path = $this->collection->getPathPrefix() . $path;

		// If the handler is a not a callable, prepend the handler prefix.
		if (!is_callable($handler))
		{
			$this->handler = $this->collection->getHandlerPrefix() . $handler;
		}
		else
		{
			$this->handler = $handler;
		}

		*/
	}

	/**
	 * Set the name of this route, this name can be used to retrieve a route by name.
	 *
	 * @param string $name The name of this route.
	 *
	 * @return Route This route instance.
	 */
	public function setName(string $name): Route
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Return the name of this route.
	 *
	 * @return string The name of this route.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Set a rule for a parameter with a regex.
	 *
	 * The parentheses for a capturing group are added by this method.
	 *
	 * @param string $key The parameter name as displayed in the path without the curly braces.
	 * @param string $regex The regular expression to match for the parameter value.
	 *
	 * @return Route This instance to allow for chaining methods.
	 */
	public function setRule(string $key, string $regex): Route
	{
		$this->rules[$key] = '(' . $regex . ')'; // Automatically add capturing group around regex.

		return $this;
	}
}