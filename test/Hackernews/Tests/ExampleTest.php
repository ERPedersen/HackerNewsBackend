<?php

namespace Hackernews\Tests;

use Hackernews\Example;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    private $example;

    public function testReturnsCorrectOutput()
    {
        $this->example = new Example();
        self::assertEquals($this->example->hello(), "Hello Worlde");
    }

    public function testShouldNotReturnLowercase()
    {
        $this->example = new Example();
        self::assertNotEquals($this->example->hello(), "hello world");
    }
}
