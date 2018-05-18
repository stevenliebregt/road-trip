==========
Quickstart
==========

No cache example
================

This example shows you how to create a router without using cache.

.. code-block:: php

    <?php

    $router = new Router();

    $collection = new RouteCollection();
    $collection->get('/foo', function () {
        echo 'Hello, world!';
    });

    $router->addCollection($collection);

    $router->compile();

    // Check for a match.
    $match = $router->match();

Cache example
=============

.. code-block:: php

    <?php

    $router = new Router();
    $router->shouldCache(true);

    // Check if we have a cache file, if that is the case, we don't need to generate the
    // following routes and collections and generate regular expressions.
    if (!$router->hasCache()) {
        $collection = new RouteCollection();
        $collection->get('/foo', function () {
            echo 'Hello, world!';
        });

        $router->addCollection($collection);

        $router->compile();
    }

    // Check for a match.
    $match = $router->match();

