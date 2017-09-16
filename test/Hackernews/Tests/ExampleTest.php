<?php

namespace Hackernews\Tests;

use Hackernews\Example;

/**
 * Class ExampleTest
 *
 * @package Hackernews\Tests
 */
class ExampleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Example
     */
    private $example;

    /**
     * Tests for correct output.
     */
    public function testReturnsCorrectOutput()
    {
        $this->example = new Example();
        self::assertEquals($this->example->hello(), "Hello World");
    }

    /**
     * Tests that Example does not return a lowercase string.
     */
    public function testShouldNotReturnLowercase()
    {
        $this->example = new Example();
        self::assertNotEquals($this->example->hello(), "hello world");
    }
}
