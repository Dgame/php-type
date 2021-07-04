<?php

namespace Dgame\Test\Type;

use Dgame\Type\TypeOf;
use function Dgame\Type\typeof;
use Dgame\Type\TypeOfFactory;
use Dgame\Type\Validator;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionFunction;

class TypeOfTest extends TestCase
{
    public function testTypeof(): void
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
        //$this->assertTrue(typeof([self::class, 'testTypeof'])->isCallable());
        $this->assertTrue(typeof(static function (): void {
        })->isCallable());
    }

    /**
     * @throws ReflectionException
     */
    public function testReflection(): void
    {
        $functions = [
            TypeOf::IS_INT      => static function (int $foo): void {
            },
            TypeOf::IS_ARRAY    => static function (array $foo): void {
            },
            TypeOf::IS_CALLABLE => static function (callable $foo): void {
            }
        ];
        $values    = [
            TypeOf::IS_INT      => [0, 42, 23, -1],
            TypeOf::IS_ARRAY    => [[], [1], [1, 2, 3]],
            TypeOf::IS_CALLABLE => $functions,
            TypeOf::IS_OBJECT   => [new Exception()]
        ];

        foreach ($functions as $expected => $function) {
            $reflection = new ReflectionFunction($function);
            $type       = TypeOfFactory::reflection($reflection->getParameters()[0]);

            $this->assertEquals($expected, $type->getType());

            foreach ($values[$expected] as $value) {
                $this->assertTrue($type->isImplicitSame(typeof($value)));
            }
        }

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Parameter has no type');

        $reflection = new ReflectionFunction(static function ($mixed): void {
        });

        TypeOfFactory::reflection($reflection->getParameters()[0]);
    }

    public function testEquals(): void
    {
        $this->assertTrue(typeof(new self())->equals(new self()));
        $this->assertTrue(typeof(null)->equals(null));
    }

    public function testImplicit(): void
    {
        $this->assertTrue(typeof(0.0)->isImplicit(TypeOf::IS_INT));
        $this->assertTrue(typeof(0.0)->isImplicit(TypeOf::IS_FLOAT));
        $this->assertTrue(typeof(0.0)->isImplicit(TypeOf::IS_STRING));
        $this->assertTrue(typeof(0.0)->isImplicit(TypeOf::IS_NUMERIC));
        $this->assertTrue(typeof(0.0)->isImplicit(TypeOf::IS_BOOL));
        $this->assertTrue(typeof('0')->isImplicit(TypeOf::IS_INT));
        $this->assertTrue(typeof('0')->isImplicit(TypeOf::IS_FLOAT));
        $this->assertTrue(typeof('0')->isImplicit(TypeOf::IS_BOOL));
        $this->assertTrue(typeof('0')->isImplicit(TypeOf::IS_NUMERIC));
        $this->assertTrue(typeof('0')->isImplicit(TypeOf::IS_STRING));
        $this->assertTrue(typeof(false)->isImplicit(TypeOf::IS_INT));
        $this->assertTrue(typeof(false)->isImplicit(TypeOf::IS_FLOAT));
        $this->assertTrue(typeof(false)->isImplicit(TypeOf::IS_BOOL));
        $this->assertTrue(typeof(false)->isImplicit(TypeOf::IS_STRING));
    }

    public function testBuiltin(): void
    {
        $this->assertTrue(typeof(0)->isBuiltin());
        $this->assertTrue(typeof(0.0)->isBuiltin());
        $this->assertTrue(typeof(true)->isBuiltin());
        $this->assertTrue(typeof('a')->isBuiltin());
        $this->assertTrue(typeof('0')->isBuiltin());
        $this->assertTrue(typeof([])->isBuiltin());
        $this->assertFalse(typeof(new self())->isBuiltin());
        $type = new TypeOf(TypeOf::IS_MIXED);
        $this->assertTrue($type->isBuiltIn());
    }

    public function testIsSame(): void
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

    public function testIsImplicitSame(): void
    {
        $this->assertTrue(typeof(0.0)->isImplicitSame(typeof(0)));
        $this->assertTrue(typeof(0)->isImplicitSame(typeof(0.0)));
        $this->assertTrue(typeof(0)->isImplicitSame(typeof('4')));
        $this->assertTrue(typeof(0)->isImplicitSame(typeof('a')));
        $this->assertTrue(typeof('a')->isImplicitSame(typeof('b')));
        $this->assertFalse(typeof('a')->isImplicitSame(typeof(42)));
        $this->assertTrue(typeof('0')->isImplicitSame(typeof(42)));
    }

    public function testAccept(): void
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

        $type = new TypeOf(TypeOf::IS_MIXED);
        $this->assertTrue($type->accept('abc'));
        $this->assertTrue($type->accept('42'));
        $this->assertTrue($type->accept(42));
        $this->assertTrue($type->accept(3.14));
        $this->assertTrue($type->accept(null));
    }

    public function testDefaultValue(): void
    {
        $this->assertEquals(0, typeof(42)->getDefaultValue());
        $this->assertEquals(0.0, typeof(2.3)->getDefaultValue());
        $this->assertEquals('', typeof('abc')->getDefaultValue());
        $this->assertEquals(false, typeof(true)->getDefaultValue());
        $this->assertEquals([], typeof([1, 2, 3])->getDefaultValue());
        $this->assertEquals(null, typeof(null)->getDefaultValue());
        $this->assertEquals(null, typeof(new self())->getDefaultValue());
        $type = new TypeOf(TypeOf::IS_MIXED);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testExport(): void
    {
        $this->assertEquals('int', typeof(42)->export());
        $this->assertEquals('float', typeof(2.3)->export());
        $this->assertEquals('string', typeof('abc')->export());
        $this->assertEquals('bool', typeof(true)->export());
        $this->assertEquals('array', typeof([1, 2, 3])->export());
        $this->assertEquals('null', typeof(null)->export());
        $this->assertEquals('object', typeof(new self())->export());
        $type = new TypeOf(TypeOf::IS_MIXED);
        $this->assertEquals('mixed', $type->export());
    }

    public function testAlias(): void
    {
        $this->assertEquals(TypeOf::IS_INT, TypeOf::alias('int'));
        $this->assertEquals(TypeOf::IS_INT, TypeOf::alias('integer'));
        $this->assertEquals(TypeOf::IS_INT, TypeOf::alias('long'));
        $this->assertEquals(TypeOf::IS_NUMERIC, TypeOf::alias('numeric'));
        $this->assertEquals(TypeOf::IS_FLOAT, TypeOf::alias('float'));
        $this->assertEquals(TypeOf::IS_FLOAT, TypeOf::alias('double'));
        $this->assertEquals(TypeOf::IS_FLOAT, TypeOf::alias('real'));
        $this->assertEquals(TypeOf::IS_BOOL, TypeOf::alias('bool'));
        $this->assertEquals(TypeOf::IS_BOOL, TypeOf::alias('boolean'));
        $this->assertEquals(TypeOf::IS_STRING, TypeOf::alias('string'));
        $this->assertEquals(TypeOf::IS_ARRAY, TypeOf::alias('array'));
        $this->assertEquals(TypeOf::IS_OBJECT, TypeOf::alias('object'));
        $this->assertEquals(TypeOf::IS_NULL, TypeOf::alias('null'));
        $this->assertEquals(TypeOf::NONE, TypeOf::alias('abc'));
    }

    public function testEmptyValue(): void
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

    public function testValidValue(): void
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

    public function testImport(): void
    {
        $this->assertTrue(TypeOf::import('int')->isInt());
        $this->assertTrue(TypeOf::import('null')->isNull());
        $this->assertTrue(TypeOf::import('mixed')->isMixed());
    }
}
