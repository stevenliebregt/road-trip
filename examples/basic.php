<?php

use StevenLiebregt\RoadTrip\Router;
use StevenLiebregt\RoadTrip\RouteCollection;

// Load the Composer autoloader.
require __DIR__ . '/../vendor/autoload.php';

// Create the router instance.
$router = new Router();

// Create a route collection.
$collection = new RouteCollection();

// Add some routes to the collection.
$collection->get('/products', 'ProductController.index');
$collection->post('/products', 'ProductController.create');

// Add the collection to the router.
$router->addCollection($collection);

// Compile all the routes in all the collections in the router so we are ready to match.
$router->compile();

// Check for a match.
$match = $router->match('THE_CURRENT_REQUEST_METHOD', 'THE_CURRENT_REQUEST_URI');