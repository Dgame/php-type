<?php

declare(strict_types=1);

namespace Dgame\Test\Type;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\MixedType;
use Dgame\Type\NullType;
use Dgame\Type\ObjectType;
use Dgame\Type\StringType;
use Dgame\Type\UnionType;
use Dgame\Type\VoidType;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class AcceptanceTest
 * @package Dgame\Test\Type
 */
final class ValueAcceptanceTest extends TestCase
{
    public function testObjectAcceptValue(): void
    {
        $type = new ObjectType();
        $this->assertTrue($type->acceptValue(new stdClass(), false));
        $this->assertTrue($type->acceptValue(new stdClass(), true));
    }

    public function testStringAcceptValue(): void
    {
        $type = new StringType();
        $this->assertTrue($type->acceptValue('abc', true));
        $this->assertTrue($type->acceptValue('42', true));
        $this->assertFalse($type->acceptValue(42, true));
        $this->assertTrue($type->acceptValue(42, false));
    }

    public function testIntAcceptValue(): void
    {
        $type = new IntType();
        $this->assertFalse($type->acceptValue('abc', true));
        $this->assertTrue($type->acceptValue('42', false));
        $this->assertFalse($type->acceptValue('42', true));
        $this->assertTrue($type->acceptValue(42, true));
        $this->assertTrue($type->acceptValue(42, false));
        $this->assertTrue($type->acceptValue(4.2, false));
        $this->assertFalse($type->acceptValue(4.2, true));
    }

    public function testFloatAcceptValue(): void
    {
        $type = new FloatType();
        $this->assertFalse($type->acceptValue('abc', true));
        $this->assertTrue($type->acceptValue('42', false));
        $this->assertFalse($type->acceptValue('42', true));
        $this->assertTrue($type->acceptValue(42, true));
        $this->assertTrue($type->acceptValue(42, false));
        $this->assertTrue($type->acceptValue(4.2, false));
        $this->assertTrue($type->acceptValue(4.2, true));
    }

    public function testBoolAcceptValue(): void
    {
        $type = new BoolType();
        $this->assertFalse($type->acceptValue('abc', true));
        $this->assertTrue($type->acceptValue('42', false));
        $this->assertFalse($type->acceptValue('42', true));
        $this->assertFalse($type->acceptValue(42, true));
        $this->assertTrue($type->acceptValue(42, false));
        $this->assertTrue($type->acceptValue(4.2, false));
        $this->assertFalse($type->acceptValue(4.2, true));
        $this->assertTrue($type->acceptValue(true, true));
        $this->assertTrue($type->acceptValue(false, true));
        $this->assertTrue($type->acceptValue(true, false));
        $this->assertTrue($type->acceptValue(false, false));
        $this->assertFalse($type->acceptValue(1, true));
        $this->assertFalse($type->acceptValue(0, true));
        $this->assertTrue($type->acceptValue(1, false));
        $this->assertTrue($type->acceptValue(0, false));
    }

    public function testNullAcceptValue(): void
    {
        $type = new NullType();
        $this->assertTrue($type->acceptValue(null, true));
        $this->assertTrue($type->acceptValue(null, false));
        $this->assertTrue($type->acceptValue([], false));
        $this->assertFalse($type->acceptValue([], true));
        $this->assertFalse($type->acceptValue(0, true));
        $this->assertTrue($type->acceptValue(0, false));
        $this->assertFalse($type->acceptValue('', true));
        $this->assertTrue($type->acceptValue('', false));
    }

    public function testMixedAcceptValue(): void
    {
        $type = new MixedType();
        $this->assertTrue($type->acceptValue('', true));
        $this->assertTrue($type->acceptValue('', false));
        $this->assertTrue($type->acceptValue('abc', true));
        $this->assertTrue($type->acceptValue('abc', false));
        $this->assertTrue($type->acceptValue('42', true));
        $this->assertTrue($type->acceptValue('42', false));
        $this->assertTrue($type->acceptValue(null, true));
        $this->assertTrue($type->acceptValue(null, false));
        $this->assertTrue($type->acceptValue(true, true));
        $this->assertTrue($type->acceptValue(false, false));
        $this->assertTrue($type->acceptValue([], true));
        $this->assertTrue($type->acceptValue([], false));
        $this->assertTrue($type->acceptValue([1, 2, 3], true));
        $this->assertTrue($type->acceptValue(['#; #B#'], false));
        $this->assertTrue($type->acceptValue(42, true));
        $this->assertTrue($type->acceptValue(42, false));
        $this->assertTrue($type->acceptValue(4.2, true));
        $this->assertTrue($type->acceptValue(4.2, false));
    }

    public function testVoidAcceptValue(): void
    {
        $type = new VoidType();
        $this->assertFalse($type->acceptValue('', true));
        $this->assertFalse($type->acceptValue('', false));
        $this->assertFalse($type->acceptValue('abc', true));
        $this->assertFalse($type->acceptValue('abc', false));
        $this->assertFalse($type->acceptValue('42', true));
        $this->assertFalse($type->acceptValue('42', false));
        $this->assertFalse($type->acceptValue(null, true));
        $this->assertFalse($type->acceptValue(null, false));
        $this->assertFalse($type->acceptValue(true, true));
        $this->assertFalse($type->acceptValue(false, false));
        $this->assertFalse($type->acceptValue([], true));
        $this->assertFalse($type->acceptValue([], false));
        $this->assertFalse($type->acceptValue([1, 2, 3], true));
        $this->assertFalse($type->acceptValue(['#; #B#'], false));
        $this->assertFalse($type->acceptValue(42, true));
        $this->assertFalse($type->acceptValue(42, false));
        $this->assertFalse($type->acceptValue(4.2, true));
        $this->assertFalse($type->acceptValue(4.2, false));
    }

    public function testIntArrayAcceptValue(): void
    {
        $type = new ArrayType(new IntType());
        $this->assertTrue($type->acceptValue([], true));
        $this->assertTrue($type->acceptValue([], false));
        $this->assertTrue($type->acceptValue([1, 2, 3], true));
        $this->assertTrue($type->acceptValue([1, 2, 3], false));
        $this->assertFalse($type->acceptValue(['a', 'b'], true));
        $this->assertFalse($type->acceptValue(['1', '2'], true));
        $this->assertTrue($type->acceptValue(['1', '2'], false));
    }

    public function testUnionTypeAcceptValue(): void
    {
        $type = new UnionType(new IntType(), new StringType());
        $this->assertTrue($type->acceptValue('abc', true));
        $this->assertTrue($type->acceptValue('42', false));
        $this->assertTrue($type->acceptValue('42', true));
        $this->assertTrue($type->acceptValue(42, true));
    }
}
