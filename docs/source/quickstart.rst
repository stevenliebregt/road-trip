==========
Quickstart
==========

This page provides a quick introduction to Road Trip, and shows some examples. If you have not installed Road Trip yet,
please head over to the ``overview`` page.

Basic example
=============

This basic example will get you started right away.

.. code-block:: php

    <?php

    use StevenLiebregt\RoadTrip\RouteCollection;
    use StevenLiebregt\RoadTrip\Router;

    $router = new Router();

    $collection = new RouteCollection();
    $collection->get('/products', 'ProductController.index');
    $collection->post('/products', 'ProductController.create');

    $router->addCollection($collection);
    $router->compile();

    $match = $router->match('THE_CURRENT_REQUEST_METHOD', 'THE_CURRENT_REQUEST_URI');

Setting options and prefixes
============================

This example will show you how to set prefixes for path, names and handlers, and how you can set options.

.. code-block:: php

    <?php

    use StevenLiebregt\RoadTrip\RouteCollection;
    use StevenLiebregt\RoadTrip\Router;

    $router = new Router();

    $collection = new RouteCollection();
    $collection->setPathPrefix('/api');

    $collection->get('/foo', 'Foo.Action'); // The prefix resolves this to `/api/foo`.

    $router->addCollection($collection);
    $router->compile();

    $match = $router->match('THE_CURRENT_REQUEST_METHOD', 'THE_CURRENT_REQUEST_URI');

Loading multiple collections
============================

TODO

Loading collections from files
==============================

TODO

Caching routes
==============

TODO