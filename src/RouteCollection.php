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
	public function group(array $options, callable $closure)
	{
		// TODO: implement.
	}

	/**
	 * Map a GET route to this collection.
	 *
	 * @param string $path The path to match on.
	 * @param string|callable $handler The handler to call if it matches. Must be either a string to the class and
	 * method, or a callable closure.
	 */
	public function get(string $path, $handler)
	{
		// TODO: implement.
	}
}