<?php

/*
 * This file is part of Road Trip.
 *
 * (c) 2018 Steven Liebregt <stevenliebregt@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;
use StevenLiebregt\RoadTrip\RouteCollection;

final class RouteTest extends TestCase
{
    public function testCreateRegex(): void
    {
        // TODO:
    }

    public function testIsMatch(): void
    {
        // TODO:
    }

    /**
     * Hacky function to allow testing private and protected methods.
     *
     * @param RouteCollection $object
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     * @throws ReflectionException
     */
    private function invokeMethod(RouteCollection &$object, string $method, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        $result = $method->invokeArgs($object, $parameters);

        $method->setAccessible(false);

        return $result;
    }
}