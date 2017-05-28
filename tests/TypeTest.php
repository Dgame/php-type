<?php

use Dgame\Type\Type;
use PHPUnit\Framework\TestCase;
use function Dgame\Type\typeof;

class TypeTest extends TestCase
{
    public function testTypeof()
    {
        $this->assertTrue(typeof(0.0)->isFloat());
        $this->assertTrue(typeof(0)->isInt());
        $this->assertTrue(typeof('')->isString());
        $this->assertTrue(typeof('a')->isString());
        $this->assertTrue(typeof('0')->isNumeric());
        $this->assertTrue(typeof([])->isArray());
        $this->assertTrue(typeof(new self())->isObject());
        $this->assertFalse(typeof(null)->isObject());
        $this->assertTrue(typeof(null)->isNull());
        $this->assertTrue(typeof([self::class, 'testTypeof'])->isCallable());
        $this->assertTrue(typeof(function () {
        })->isCallable());
    }

    public function testFrom()
    {
        $functions = [
            Type::IS_INT      => function (int $foo) {
            },
            Type::IS_ARRAY    => function (array $foo) {
            },
            Type::IS_CALLABLE => function (callable $foo) {
            },
            Type::IS_OBJECT   => function (self $foo) {
            }
        ];
        $values    = [
            Type::IS_INT      => [0, 42, 23, -1],
            Type::IS_ARRAY    => [[], [1], [1, 2, 3]],
            Type::IS_CALLABLE => $functions,
            Type::IS_OBJECT   => [new Exception()]
        ];

        foreach ($functions as $expected => $function) {
            $reflection = new ReflectionFunction($function);
            $type       = Type::from($reflection->getParameters()[0]);

            $this->assertEquals($expected, $type->getType());

            foreach ($values[$expected] as $value) {
                $this->assertTrue($type->isImplicitSame(typeof($value)));
            }
        }

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Parameter has no type');

        $reflection = new ReflectionFunction(function ($mixed) {
        });

        Type::from($reflection->getParameters()[0]);
    }

    public function testEquals()
    {
        $this->assertTrue(typeof(new self())->equals(new self()));
        $this->assertTrue(typeof(null)->equals(null));
    }

    public function testImplicit()
    {
        $this->assertTrue(typeof(0.0)->isImplicit(Type::IS_INT));
        $this->assertTrue(typeof(0.0)->isImplicit(Type::IS_FLOAT));
        $this->assertTrue(typeof(0.0)->isImplicit(Type::IS_STRING));
        $this->assertTrue(typeof(0.0)->isImplicit(Type::IS_NUMERIC));
        $this->assertTrue(typeof(0.0)->isImplicit(Type::IS_BOOL));
        $this->assertTrue(typeof('0')->isImplicit(Type::IS_INT));
        $this->assertTrue(typeof('0')->isImplicit(Type::IS_FLOAT));
        $this->assertTrue(typeof('0')->isImplicit(Type::IS_BOOL));
        $this->assertTrue(typeof('0')->isImplicit(Type::IS_NUMERIC));
        $this->assertTrue(typeof('0')->isImplicit(Type::IS_STRING));
        $this->assertTrue(typeof(false)->isImplicit(Type::IS_INT));
        $this->assertTrue(typeof(false)->isImplicit(Type::IS_FLOAT));
        $this->assertTrue(typeof(false)->isImplicit(Type::IS_BOOL));
        $this->assertTrue(typeof(false)->isImplicit(Type::IS_STRING));
    }

    public function testBuiltin()
    {
        $this->assertTrue(typeof(0)->isBuiltin());
        $this->assertTrue(typeof(0.0)->isBuiltin());
        $this->assertTrue(typeof(true)->isBuiltin());
        $this->assertTrue(typeof('a')->isBuiltin());
        $this->assertTrue(typeof('0')->isBuiltin());
        $this->assertTrue(typeof([])->isBuiltin());
        $this->assertFalse(typeof(new self())->isBuiltin());
    }

    public function testIsSame()
    {
        $this->assertTrue(typeof(0.0)->isSame(typeof(3.14)));
        $this->assertFalse(typeof(0.0)->isSame(typeof(3)));
        $this->assertTrue(typeof(0)->isSame(typeof(4)));
        $this->assertFalse(typeof(0)->isSame(typeof(3.14)));
        $this->assertTrue(typeof('a')->isSame(typeof('b')));
        $this->assertFalse(typeof('a')->isSame(typeof('0')));
        $this->assertFalse(typeof('a')->isSame(typeof(0)));
        $this->assertTrue(typeof('0')->isSame(typeof('42')));
        $this->assertFalse(typeof('0')->isSame(typeof('a')));
        $this->assertFalse(typeof('0')->isSame(typeof(0)));
    }

    public function testIsImplicitSame()
    {
        $this->assertTrue(typeof(0.0)->isImplicitSame(typeof(0)));
        $this->assertTrue(typeof(0)->isImplicitSame(typeof(0.0)));
        $this->assertTrue(typeof(0)->isImplicitSame(typeof('4')));
        $this->assertTrue(typeof('a')->isImplicitSame(typeof('b')));
        $this->assertFalse(typeof('a')->isImplicitSame(typeof(42)));
        $this->assertTrue(typeof('0')->isImplicitSame(typeof(42)));
    }

    public function testAccept()
    {
        $this->assertTrue(typeof(0)->accept('0'));
        $this->assertTrue(typeof(0)->accept(0));
        $this->assertTrue(typeof(0)->accept(0.1));
        $this->assertTrue(typeof(0)->accept(false));
        $this->assertFalse(typeof(0)->accept(null));
        $this->assertFalse(typeof(0)->accept('abc'));
        $this->assertTrue(typeof(true)->accept(false));
        $this->assertTrue(typeof(true)->accept(1));
        $this->assertTrue(typeof(true)->accept(3.14));
        $this->assertFalse(typeof(true)->accept(null));
        $this->assertFalse(typeof(true)->accept('abc'));
    }

    public function testDefaultValue()
    {
        $this->assertEquals(0, typeof(42)->getDefaultValue());
        $this->assertEquals(0.0, typeof(2.3)->getDefaultValue());
        $this->assertEquals('', typeof('abc')->getDefaultValue());
        $this->assertEquals(false, typeof(true)->getDefaultValue());
        $this->assertEquals([], typeof([1, 2, 3])->getDefaultValue());
        $this->assertEquals(null, typeof(null)->getDefaultValue());
        $this->assertEquals(null, typeof(new self())->getDefaultValue());
    }

    public function testExport()
    {
        $this->assertEquals('int', typeof(42)->export());
        $this->assertEquals('float', typeof(2.3)->export());
        $this->assertEquals('string', typeof('abc')->export());
        $this->assertEquals('bool', typeof(true)->export());
        $this->assertEquals('array', typeof([1, 2, 3])->export());
        $this->assertEquals('null', typeof(null)->export());
        $this->assertEquals('object', typeof(new self())->export());
    }

    public function testImport()
    {
        $this->assertEquals('int', Type::import('int')->export());
        $this->assertEquals('float', Type::import('float')->export());
        $this->assertEquals('bool', Type::import('bool')->export());
        $this->assertEquals('array', Type::import('array')->export());
        $this->assertEquals('object', Type::import('object')->export());
        $this->assertNull(Type::import('abc'));
    }

    public function testEmptyValue()
    {
        $this->assertTrue(Type::isEmptyValue(''));
        $this->assertFalse(Type::isEmptyValue(' '));
        $this->assertFalse(Type::isEmptyValue('abc'));
        $this->assertFalse(Type::isEmptyValue('0'));
        $this->assertFalse(Type::isEmptyValue(0));
        $this->assertFalse(Type::isEmptyValue(false));
        $this->assertFalse(Type::isEmptyValue(true));
        $this->assertTrue(Type::isEmptyValue(null));
        $this->assertTrue(Type::isEmptyValue([]));
        $this->assertFalse(Type::isEmptyValue([1, 2, 3]));
    }

    public function testValidValue()
    {
        $this->assertFalse(Type::isValidValue(''));
        $this->assertTrue(Type::isValidValue(' '));
        $this->assertTrue(Type::isValidValue('abc'));
        $this->assertTrue(Type::isValidValue('0'));
        $this->assertTrue(Type::isValidValue(0));
        $this->assertFalse(Type::isValidValue(false));
        $this->assertTrue(Type::isValidValue(true));
        $this->assertFalse(Type::isValidValue(null));
        $this->assertFalse(Type::isValidValue([]));
        $this->assertTrue(Type::isValidValue([1, 2, 3]));
    }
}