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
	/**
	 * Add a given collection to this router instance.
	 *
	 * @param RouteCollection $collection The collection to add.
	 *
	 * @return Router This instance to allow for method chaining.
	 */
	public function addCollection(RouteCollection $collection): Router
	{
		// TODO: implement.
		pr($collection);

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
		// Check if the file exists.
		if (!file_exists($file))
		{
			throw new FileNotFoundException('The requested route file could not be found at location: ' . $file);
		}

		$collection = require $file;

		// TODO: implement.
		pr($collection);

		return $this;
	}
}