<?php

declare(strict_types=1);

namespace Dgame\Type\Tests;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\NullType;
use Dgame\Type\ObjectType;
use Dgame\Type\StringType;
use Dgame\Type\Type;
use PHPUnit\Framework\TestCase;

final class FromValueTest extends TestCase
{
    /**
     * @param mixed $value
     * @param Type  $expected
     *
     * @dataProvider provideTypeValues
     */
    public function testTypeValues(mixed $value, Type $expected): void
    {
        $this->assertEquals($expected, Type::fromValue($value));
    }

    public function provideTypeValues(): iterable
    {
        yield 0 => [0, new IntType()];
        yield -42 => [-42, new IntType()];
        yield 42 => [42, new IntType()];
        yield 4.2 => [4.2, new FloatType()];
        yield '4.2' => ['4.2', new StringType()];
        yield 'foobar' => ['foobar', new StringType()];
        yield 'true' => [true, new BoolType()];
        yield 'false' => [false, new BoolType()];
        yield '[]' => [[], new ArrayType()];
        yield '[1]' => [[1], new ArrayType(new IntType(), new IntType())];
        yield '[1, 2, 3]' => [[1, 2, 3], new ArrayType(new IntType(), new IntType())];
        yield '[true, false]' => [[true, false], new ArrayType(new BoolType(), new IntType())];
        yield '["foo" => true, "bar" => false]' => [['foo' => true, 'bar' => false], new ArrayType(new BoolType(), new StringType())];
        yield 'null' => [null, new NullType()];
        yield 'Closure' => [static fn () => null, new ObjectType('Closure')];
    }
}
