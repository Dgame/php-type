<?php

use Dgame\Type\Type;
use Dgame\Type\TypeFactory;
use Dgame\Type\Validator;
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
        $this->assertTrue(typeof(false)->isBool());
        $this->assertTrue(typeof(true)->isBool());
        $this->assertTrue(typeof([])->isArray());
        $this->assertTrue(typeof(new self())->isObject());
        $this->assertFalse(typeof(null)->isObject());
        $this->assertTrue(typeof(null)->isNull());
        $this->assertTrue(typeof([self::class, 'testTypeof'])->isCallable());
        $this->assertTrue(typeof(function () {
        })->isCallable());
    }

    public function testReflection()
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
            $type       = TypeFactory::reflection($reflection->getParameters()[0]);

            $this->assertEquals($expected, $type->getType());

            foreach ($values[$expected] as $value) {
                $this->assertTrue($type->isImplicitSame(typeof($value)));
            }
        }

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Parameter has no type');

        $reflection = new ReflectionFunction(function ($mixed) {
        });

        TypeFactory::reflection($reflection->getParameters()[0]);
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
        $type = new Type(Type::IS_MIXED);
        $this->assertTrue($type->isBuiltIn());
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
        $this->assertTrue(typeof(0)->isImplicitSame(typeof('a')));
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

        $type = new Type(Type::IS_MIXED);
        $this->assertTrue($type->accept('abc'));
        $this->assertTrue($type->accept('42'));
        $this->assertTrue($type->accept(42));
        $this->assertTrue($type->accept(3.14));
        $this->assertTrue($type->accept(null));
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
        $type = new Type(Type::IS_MIXED);
        $this->assertEquals(null, $type->getDefaultValue());
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
        $type = new Type(Type::IS_MIXED);
        $this->assertEquals('mixed', $type->export());
    }

    public function testImport()
    {
        $this->assertEquals(Type::IS_INT, Type::alias('int'));
        $this->assertEquals(Type::IS_INT, Type::alias('integer'));
        $this->assertEquals(Type::IS_INT, Type::alias('long'));
        $this->assertEquals(Type::IS_NUMERIC, Type::alias('numeric'));
        $this->assertEquals(Type::IS_FLOAT, Type::alias('float'));
        $this->assertEquals(Type::IS_FLOAT, Type::alias('double'));
        $this->assertEquals(Type::IS_FLOAT, Type::alias('real'));
        $this->assertEquals(Type::IS_BOOL, Type::alias('bool'));
        $this->assertEquals(Type::IS_BOOL, Type::alias('boolean'));
        $this->assertEquals(Type::IS_STRING, Type::alias('string'));
        $this->assertEquals(Type::IS_ARRAY, Type::alias('array'));
        $this->assertEquals(Type::IS_OBJECT, Type::alias('object'));
        $this->assertEquals(Type::IS_NULL, Type::alias('null'));
        $this->assertEquals(Type::NONE, Type::alias('abc'));
    }

    public function testEmptyValue()
    {
        $this->assertTrue(Validator::verify('')->isEmptyValue());
        $this->assertFalse(Validator::verify(' ')->isEmptyValue());
        $this->assertFalse(Validator::verify('abc')->isEmptyValue());
        $this->assertFalse(Validator::verify('0')->isEmptyValue());
        $this->assertFalse(Validator::verify(0)->isEmptyValue());
        $this->assertFalse(Validator::verify(false)->isEmptyValue());
        $this->assertFalse(Validator::verify(true)->isEmptyValue());
        $this->assertTrue(Validator::verify(null)->isEmptyValue());
        $this->assertTrue(Validator::verify([])->isEmptyValue());
        $this->assertFalse(Validator::verify([1, 2, 3])->isEmptyValue());
    }

    public function testValidValue()
    {
        $this->assertFalse(Validator::verify('')->isValidValue());
        $this->assertTrue(Validator::verify(' ')->isValidValue());
        $this->assertTrue(Validator::verify('abc')->isValidValue());
        $this->assertTrue(Validator::verify('0')->isValidValue());
        $this->assertTrue(Validator::verify(0)->isValidValue());
        $this->assertFalse(Validator::verify(false)->isValidValue());
        $this->assertTrue(Validator::verify(true)->isValidValue());
        $this->assertFalse(Validator::verify(null)->isValidValue());
        $this->assertFalse(Validator::verify([])->isValidValue());
        $this->assertTrue(Validator::verify([1, 2, 3])->isValidValue());
    }
}