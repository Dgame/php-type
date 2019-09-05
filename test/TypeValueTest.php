<?php

declare(strict_types=1);

namespace Dgame\Test\Type;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\CallableType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\NullType;
use Dgame\Type\ObjectType;
use Dgame\Type\ResourceType;
use Dgame\Type\StringType;
use Dgame\Type\Type;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class TypeValueTest
 * @package Dgame\Test\Type
 */
final class TypeValueTest extends TestCase
{
    public function testFloat(): void
    {
        $type = Type::fromValue(3.14);
        $this->assertEquals('float', $type->getDescription());
        $this->assertEquals(0.0, $type->getDefaultValue());
        $this->assertEquals(new FloatType(), $type);
    }

    public function testInt(): void
    {
        $type = Type::fromValue(42);
        $this->assertEquals('int', $type->getDescription());
        $this->assertEquals(0, $type->getDefaultValue());
        $this->assertEquals(new IntType(), $type);

        $type = Type::fromValue('42');
        $this->assertEquals('int', $type->getDescription());
        $this->assertEquals(0, $type->getDefaultValue());
        $this->assertEquals(new IntType(), $type);
    }

    public function testString(): void
    {
        $type = Type::fromValue('3.14');
        $this->assertEquals('float', $type->getDescription());
        $this->assertEquals(0, $type->getDefaultValue());
        $this->assertEquals(new FloatType(), $type);

        $type = Type::fromValue('');
        $this->assertEquals('string', $type->getDescription());
        $this->assertEquals('', $type->getDefaultValue());
        $this->assertEquals(new StringType(), $type);

        $type = Type::fromValue(' ');
        $this->assertEquals('string', $type->getDescription());
        $this->assertEquals('', $type->getDefaultValue());
        $this->assertEquals(new StringType(), $type);
    }

    public function testBool(): void
    {
        $type = Type::fromValue(false);
        $this->assertEquals('bool', $type->getDescription());
        $this->assertEquals(false, $type->getDefaultValue());
        $this->assertEquals(new BoolType(), $type);

        $type = Type::fromValue(true);
        $this->assertEquals('bool', $type->getDescription());
        $this->assertEquals(false, $type->getDefaultValue());
        $this->assertEquals(new BoolType(), $type);

        $type = Type::fromValue('yes');
        $this->assertEquals('string', $type->getDescription());
        $this->assertEquals('', $type->getDefaultValue());
        $this->assertEquals(new StringType(), $type);
    }

    public function testNull(): void
    {
        $type = Type::fromValue(null);
        $this->assertEquals('null', $type->getDescription());
        $this->assertEquals(null, $type->getDefaultValue());
        $this->assertEquals(new NullType(), $type);
    }

    public function testCallable(): void
    {
        $fn = static function (): void {
        };

        $type = Type::fromValue($fn);
        $this->assertEquals('callable', $type->getDescription());
        $this->assertEquals(null, $type->getDefaultValue());
        $this->assertEquals(new CallableType(), $type);

        $type = Type::fromValue([$this, 'testCallable']);
        $this->assertEquals('callable', $type->getDescription());
        $this->assertEquals(null, $type->getDefaultValue());
        $this->assertEquals(new CallableType(), $type);
    }

    public function testArray(): void
    {
        $type = Type::fromValue([]);
        $this->assertEquals('array', $type->getDescription());
        $this->assertEquals([], $type->getDefaultValue());
        $this->assertEquals(new ArrayType(), $type);

        $type = Type::fromValue([1, 2, 3]);
        $this->assertEquals('array', $type->getDescription());
        $this->assertEquals([], $type->getDefaultValue());
        $this->assertEquals(new ArrayType(), $type);
    }

    public function testObject(): void
    {
        $type = Type::fromValue(new stdClass());
        $this->assertEquals('object', $type->getDescription());
        $this->assertEquals(null, $type->getDefaultValue());
        $this->assertEquals(new ObjectType('object'), $type);
    }

    public function testResource(): void
    {
        $f    = fopen(__DIR__ . '/TypeParseTest.php', 'rb');
        $type = Type::fromValue($f);
        fclose($f);
        $this->assertEquals('resource', $type->getDescription());
        $this->assertEquals(null, $type->getDefaultValue());
        $this->assertEquals(new ResourceType(), $type);
    }
}
