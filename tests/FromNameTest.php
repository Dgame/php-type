<?php

declare(strict_types=1);

namespace Dgame\Type\Tests;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\MixedType;
use Dgame\Type\NullType;
use Dgame\Type\ObjectType;
use Dgame\Type\StringType;
use Dgame\Type\Type;
use Dgame\Type\UnionType;
use PHPUnit\Framework\TestCase;

final class FromNameTest extends TestCase
{
    /**
     * @param string $name
     * @param Type   $expected
     *
     * @dataProvider provideTypeNames
     */
    public function testTypeNames(string $name, Type $expected): void
    {
        $this->assertEquals($expected, Type::fromName($name));
    }

    public function provideTypeNames(): iterable
    {
        yield 'mixed' => ['mixed', new MixedType()];
        yield 'int' => ['int', new IntType()];
        yield 'integer' => ['integer', new IntType()];
        yield 'float' => ['float', new FloatType()];
        yield 'double' => ['double', new FloatType()];
        yield 'real' => ['real', new FloatType()];
        yield 'string' => ['string', new StringType()];
        yield '?string' => ['?string', new UnionType(new StringType(), new NullType())];
        yield 'string|null' => ['string|null', new UnionType(new StringType(), new NullType())];
        yield 'string|int|null' => ['string|int|null', new UnionType(new StringType(), new IntType(), new NullType())];
        yield 'array' => ['array', new ArrayType()];
        yield 'array<int>' => ['array<int>', new ArrayType(new IntType(), new IntType())];
        yield 'array<string, bool>' => ['array<string, bool>', new ArrayType(new BoolType(), new StringType())];
        yield 'array<string, array<bool>>' => [
            'array<string, array<bool>>',
            new ArrayType(new ArrayType(new BoolType(), new IntType()), new StringType())
        ];
        yield 'int[]' => ['int[]', new ArrayType(new IntType(), new IntType())];
        yield 'string[]' => ['string[]', new ArrayType(new StringType(), new IntType())];
        yield 'object' => ['object', new ObjectType('object')];
        yield self::class => [self::class, new ObjectType(self::class)];
    }
}
