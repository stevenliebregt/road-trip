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
	 * @var string $name Holds the name of this route.
	 */
	private $name;

	/**
	 * Set the name of this route.
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

	public function where(string $name, string $regex): Route
	{
		// TODO: implement.

		return $this;
	}
}