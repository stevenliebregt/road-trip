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
	 * Set the path prefix for this collection instance.
	 *
	 * @param string $pathPrefix The new path prefix.
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setPathPrefix(?string $pathPrefix): RouteCollection
	{
		$this->pathPrefix = $pathPrefix;

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
	 *
	 * @return RouteCollection This instance to allow for method chaining.
	 */
	public function setHandlerPrefix(?string $handlerPrefix): RouteCollection
	{
		$this->handlerPrefix = $handlerPrefix;

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
}