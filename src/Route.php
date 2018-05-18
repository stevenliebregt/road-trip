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
    const PARAMETER_SYMBOL_LEFT = "{";
    const PARAMETER_SYMBOL_RIGHT = "}";

    /** @var RouteCollection The collection in which this route resides. */
    private $collection;

    /** @var string Holds the HTTP method that this route should match for. */
    private $method;

    /** @var string Holds the path that this route should be matched on. */
    private $path;

    /** @var string|callable Holds the handler for this route. */
    private $handler;

    /** @var array Holds the rules for the parameters in the path. */
    private $rules = [];

    /** @var string Holds the name of this route. */
    private $name;

    /** @var bool Defines if the route is static or has parameters. */
    private $isStatic;

    /** @var string Regular expression for routes with parameterized parts. */
    private $regex;

    /** @var array Holds the parameters for this route. */
    private $parameters = [];

    public function __construct(RouteCollection $collection, string $method, string $path, $handler)
    {
        $this->collection = $collection;
        $this->method = $method;
        $this->path = $this->collection->getPathPrefix() . $path;
        $this->handler = is_string($handler) ?
            $this->collection->getHandlerPrefix() . $handler :
            $handler;

        // Check if route is static by checking if it contains a placeholder character.
        $this->isStatic = (bool) !stripos($path, Route::PARAMETER_SYMBOL_LEFT);
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
        // TODO: retrieve $this->collection->getNamePrefix();

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
     * If this method is not called, the default regular expression allows any character except a forward slash.
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

    /**
     * Compile this route.
     *
     * First create the regular expression if necessary, and then unset some variables to prevent clutter when caching.
     *
     * @return Route The compiled route.
     */
    public function compile(): Route
    {
        // Check if we need to create a regular expression.
        if (!$this->isStatic) {
            $this->createRegex();
        }

        // The following variables will not be useful after caching, so we'll delete them.
        $this->collection = null;
        $this->rules = null;

        return $this;
    }

    /**
     * Create a regular expression that matches this route's path, parameters and set rules.
     */
    private function createRegex(): void
    {
        $regex = '/^'; // Start of the regex, which matches start of string.

        // Loop through route parts to define which parts are parameters.
        $parts = explode('/', ltrim($this->path, '/'));
        foreach ($parts as $part) {
            $regex .= '\/'; // Matches the starting slash.

            // Check if the parameter symbol is in this part, if that is the case, it is parameter, otherwise it is a
            // static part.
            if (stripos($part, Route::PARAMETER_SYMBOL_LEFT) === false) {
                $regex .= $part; // Match this part exactly as given.
                continue;
            }

            // If we are here, then we're dealing with a parameter.
            $name = str_ireplace([Route::PARAMETER_SYMBOL_LEFT, Route::PARAMETER_SYMBOL_RIGHT], '', $part);
            $this->parameters[] = $name;
            $regex .= isset($this->rules[$name]) ? '(' . $this->rules[$name] . ')' : '([^\/]+)';
        }

        $regex .= '$/'; // End of the regex, which maters end of string.
        $this->regex = $regex;
    }
}