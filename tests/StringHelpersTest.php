<?php

use PHPUnit\Framework\TestCase;
use Lxr\Model\StringHelpers;

class StringHelpersTest extends TestCase
{
    public function testReplace()
    {
        $this->assertEquals('hello world', StringHelpers::replace('planet', 'world', 'hello planet'));
        $this->assertEquals('hello world', StringHelpers::replace(['planet'], ['world'], 'hello planet', false));
    }

    public function testUpper()
    {
        $this->assertEquals('HELLO WORLD', StringHelpers::upper('hello world'));
    }

    public function testLower()
    {
        $this->assertEquals('hello world', StringHelpers::lower('HELLO WORLD'));
    }

    public function testUcfirst()
    {
        $this->assertEquals('Hello world', StringHelpers::ucfirst('hello world'));
    }

    public function testSubstr()
    {
        $this->assertEquals('Hello', StringHelpers::substr('Hello world', 0, 5));
        $this->assertEquals('world', StringHelpers::substr('Hello world', 6));
    }

    public function testStudly()
    {
        $this->assertEquals('HelloWorld', StringHelpers::studly('hello world'));
        $this->assertEquals('HelloWorld', StringHelpers::studly('hello_world'));
    }

    public function testSnake()
    {
        $this->assertEquals('hello_world', StringHelpers::snake('HelloWorld'));
        $this->assertEquals('hello-world', StringHelpers::snake('HelloWorld', '-'));
    }
}