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

final class RouteCollectionTest extends TestCase
{
    /**
     * Tests whether a path prefix can be set.
     */
    public function testPathPrefixIsSet(): void
    {
        $collection = new RouteCollection();
        $collection->setPathPrefix('/foo');

        $this->assertSame('/foo', $collection->getPathPrefix());
    }

    /**
     * Tests whether path prefixes get appended successfully.
     */
    public function testPathPrefixIsAppended(): void
    {
        $collection = new RouteCollection();
        $collection->setPathPrefix('/foo');

        $this->assertSame('/foo', $collection->getPathPrefix());

        $collection->setPathPrefix('/bar', true);

        $this->assertSame('/foo/bar', $collection->getPathPrefix());
    }

    /**
     * Tests whether handler prefixes are set successfully.
     */
    public function testHandlerPrefixIsSet(): void
    {
        $collection = new RouteCollection();
        $collection->setHandlerPrefix('Api\\');
        
        $this->assertSame('Api\\', $collection->getHandlerPrefix());
    }

    /**
     * Tests whether handler prefixes are appended successfully.
     */
    public function testHandlerPrefixIsAppended(): void
    {
        $collection = new RouteCollection();
        $collection->setHandlerPrefix('Api\\');

        $this->assertSame('Api\\', $collection->getHandlerPrefix());

        $collection->setHandlerPrefix('V1\\', true);

        $this->assertSame('Api\\V1\\', $collection->getHandlerPrefix());
    }

    public function testOptionsAreSet(): void
    {
        $collection = new RouteCollection();
        $collection->setOption('something', 'some-value');

        $this->assertSame('some-value', $collection->getOption('something'));
        $this->assertSame([
            'pathPrefix' => null,
            'handlerPrefix' => null,
            'something' => 'some-value',
        ], $collection->getOptions());

        $collection->setOptions([
            'other' => 'thing',
            'number' => 123.438,
        ]);

        $this->assertSame(123.438, $collection->getOption('number'));
        $this->assertSame([
            'pathPrefix' => null,
            'handlerPrefix' => null,
            'something' => 'some-value',
            'other' => 'thing',
            'number' => 123.438,
        ], $collection->getOptions());
    }

    /**
     * Tests whether the RouteCollection instance has the same options and prefixes after using the
     * RouteCollection::clone method.
     */
    public function testHasSameOptionsAfterClone(): void
    {
        // Create collection.
        $collection = new RouteCollection();
        $collection->setPathPrefix('/foo');
        $collection->setOptions([
            'bar' => 'something',
            'value' => 4.20,
        ]);

        // Create a clone
        /** @var RouteCollection $clone */
        $clone = $this->invokeMethod($collection, 'clone');

        $this->assertEquals($collection, $clone);
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
