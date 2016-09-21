<?php

use PHPUnit\Framework\TestCase;
use function Dgame\Type\typeof;

class TestType extends TestCase
{
    public function testTypeof()
    {
        $this->assertTrue(typeof(0.0)->isFloat());
        $this->assertTrue(typeof(0)->isInt());
        $this->assertTrue(typeof('')->isString());
        $this->assertTrue(typeof('a')->isString());
        $this->assertTrue(typeof('0')->isNumeric());
        $this->assertTrue(typeof([])->isArray());
        $this->assertTrue(typeof(new FooBar())->isObject());
        $this->assertTrue(typeof(new FooBar())->is(FooBar::class));
        $this->assertFalse(typeof(null)->is(FooBar::class));
        $this->assertTrue(typeof(null)->isNull());
    }

    public function testImplicit()
    {
        $this->assertTrue(typeof(0.0)->isImplicit('int'));
        $this->assertTrue(typeof(0.0)->isImplicit('string'));
        $this->assertTrue(typeof('0')->isImplicit('int'));
        $this->assertTrue(typeof('0')->isImplicit('float'));
        $this->assertTrue(typeof('0')->isImplicit('bool'));
        $this->assertTrue(typeof('0')->isImplicit('string'));
    }

    public function testBuiltin()
    {
        $this->assertTrue(typeof(0)->isBuiltin());
        $this->assertTrue(typeof(0.0)->isBuiltin());
        $this->assertTrue(typeof(true)->isBuiltin());
        $this->assertTrue(typeof('a')->isBuiltin());
        $this->assertTrue(typeof('0')->isBuiltin());
        $this->assertTrue(typeof([])->isBuiltin());
        $this->assertFalse(typeof(new FooBar())->isBuiltin());
    }

    public function testIsSameAs()
    {
        $this->assertTrue(typeof(0.0)->isSameAs(typeof(3.14)));
        $this->assertFalse(typeof(0.0)->isSameAs(typeof(3)));
        $this->assertTrue(typeof(0)->isSameAs(typeof(4)));
        $this->assertFalse(typeof(0)->isSameAs(typeof(3.14)));
        $this->assertTrue(typeof('a')->isSameAs(typeof('b')));
        $this->assertFalse(typeof('a')->isSameAs(typeof('0')));
        $this->assertFalse(typeof('a')->isSameAs(typeof(0)));
        $this->assertTrue(typeof('0')->isSameAs(typeof('42')));
        $this->assertFalse(typeof('0')->isSameAs(typeof('a')));
        $this->assertFalse(typeof('0')->isSameAs(typeof(0)));
    }

    public function testIsConvertibleTo()
    {
        $this->assertTrue(typeof(0.0)->isConvertibleTo(typeof(0)));
        $this->assertTrue(typeof(0)->isConvertibleTo(typeof(0.0)));
        $this->assertTrue(typeof(0)->isConvertibleTo(typeof('4')));
        $this->assertTrue(typeof('a')->isConvertibleTo(typeof('b')));
        $this->assertFalse(typeof('a')->isConvertibleTo(typeof(42)));
        $this->assertTrue(typeof('0')->isConvertibleTo(typeof(42)));
    }
}