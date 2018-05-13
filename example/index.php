<?php

use StevenLiebregt\RoadTrip\RouteCollection;
use StevenLiebregt\RoadTrip\Router;

// Require helpers.
require __DIR__ . '/helpers.php';

// Require autoloader.
require __DIR__ . '/../vendor/autoload.php';

// Create in-file collection.
$routeCollection = new RouteCollection();

$routeCollection->get('/foo', function () {
	echo 'this is bar speaking';
	exit;
});

$options = [
	'prefix' => '/test',
];

$routeCollection->group($options, function ($routeCollection) {
	$routeCollection->get('/new', function () {
		echo 'this is new test';
		exit;
	});
});

// Create router
$router = new Router();

$router->addCollection($routeCollection);
$router->addCollectionFromFile( __DIR__ . '/web-routes.php' );

