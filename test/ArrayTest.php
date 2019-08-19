<?php

declare(strict_types=1);

namespace Dgame\Test\Type;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\IntType;
use Dgame\Type\MixedType;
use Dgame\Type\NullType;
use Dgame\Type\StringType;
use Dgame\Type\VoidType;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayTest
 * @package Dgame\Test\Type
 */
final class ArrayTest extends TestCase
{
    public function testToArray(): void
    {
        $type = new IntType();
        $this->assertEquals(new ArrayType(new IntType()), $type->toArray());

        $type = new StringType();
        $this->assertEquals(new ArrayType(new StringType()), $type->toArray());

        $type = new MixedType();
        $this->assertEquals(new ArrayType(new MixedType()), $type->toArray());

        $type = new ArrayType();
        $this->assertEquals(new ArrayType(new ArrayType()), $type->toArray());

        $type = new VoidType();
        $this->assertEquals(new ArrayType(new VoidType()), $type->toArray());

        $type = new BoolType();
        $this->assertEquals(new ArrayType(new BoolType()), $type->toArray());

        $type = new NullType();
        $this->assertEquals(new ArrayType(new NullType()), $type->toArray());

        $type = new ArrayType(new NullType());
        $this->assertEquals(new ArrayType(new ArrayType(new NullType())), $type->toArray());
    }
}
