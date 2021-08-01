<?php

declare(strict_types=1);

namespace Dgame\Type\Tests;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\FalseType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\NullType;
use Dgame\Type\StringType;
use Dgame\Type\Type;
use Dgame\Type\UnionType;
use PHPUnit\Framework\TestCase;

/**
 *     final public function is(self $type): bool
 *
 *
 *
 *
 * public function allowsNull(): bool
 *
 *
 * abstract public function isBuiltIn(): bool;
 */
final class TypeTest extends TestCase
{
    /**
     * @param Type  $type
     * @param mixed $value
     * @param bool  $is
     *
     * @dataProvider provideAssignableValues
     */
    public function testAccept(Type $type, mixed $value, bool $is = false): void
    {
        $this->assertTrue($type->accept($value));
        if ($is) {
            $this->assertTrue($type->is(Type::fromValue($value)));
        }
    }

    /**
     * @param Type  $type
     * @param mixed $value
     * @param bool  $is
     *
     * @dataProvider provideNotAssignableValues
     */
    public function testNotAccept(Type $type, mixed $value, bool $is = false): void
    {
        if ($is) {
            $this->assertFalse($type->is(Type::fromValue($value)));
        } else {
            $this->assertFalse($type->accept($value));
        }
    }

    public function provideAssignableValues(): iterable
    {
        yield '42 (is)' => [new IntType(), 42, true];
        yield '-1 (is)' => [new IntType(), -1, true];
        yield '1337 (is)' => [new IntType(), 1337, true];
        yield 'float 42' => [new FloatType(), 42];
        yield 'float -1' => [new FloatType(), -1];
        yield 'float 1337' => [new FloatType(), 1337];
        yield '3.14 (is)' => [new FloatType(), 3.14, true];
        yield '-3.14 (is)' => [new FloatType(), -3.14, true];
        yield 'a (is)' => [new StringType(), 'a', true];
        yield 'foo (is)' => [new StringType(), 'foo', true];
        yield 'barfoo (is)' => [new StringType(), 'barfoo', true];
        yield 'true (is)' => [new BoolType(), true, true];
        yield 'false (is)' => [new BoolType(), false, true];
        yield 'false false (is)' => [new FalseType(), false];
        yield 'null (is)' => [new NullType(), null, true];
        yield 'int|null => null' => [new UnionType(new IntType(), new NullType()), null];
        yield 'int|null => 42' => [new UnionType(new IntType(), new NullType()), 42];
        yield 'int|null => -42' => [new UnionType(new IntType(), new NullType()), -42];
        yield 'array 1, 2, 3' => [new ArrayType(new IntType(), new IntType()), [1, 2, 3]];
        yield 'array a, , foo' => [new ArrayType(new StringType(), new IntType()), ['a', '', 'foo']];
        yield 'assoc. array a, , foo' => [new ArrayType(new BoolType(), new StringType()), ['a' => true, '' => false, 'foo' => true]];
    }

    public function provideNotAssignableValues(): iterable
    {
        yield 'string 42' => [new StringType(), 42];
        yield 'string -1' => [new StringType(), -1];
        yield 'string 1337' => [new StringType(), 1337];
        yield 'string 3.14' => [new StringType(), 3.14];
        yield 'string -3.14' => [new StringType(), -3.14];
        yield 'bool 1' => [new BoolType(), 1];
        yield 'bool 0' => [new BoolType(), 0];
        yield 'false false (is)' => [new FalseType(), false, true];
        yield 'array 1, 2, 3' => [new ArrayType(new IntType(), new IntType()), ['a', '', 'foo']];
        yield 'array a, , foo' => [new ArrayType(new StringType(), new IntType()), [1, 2, 3]];
    }
}
