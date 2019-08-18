<?php

namespace Dgame\Test\Type;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\CallableType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\IterableType;
use Dgame\Type\MixedType;
use Dgame\Type\NullType;
use Dgame\Type\ObjectType;
use Dgame\Type\ResourceType;
use Dgame\Type\StringType;
use Dgame\Type\Type;
use Dgame\Type\TypeParser;
use Dgame\Type\UnionType;
use Dgame\Type\UserDefinedType;
use Dgame\Type\VoidType;
use PHPUnit\Framework\TestCase;

class TypeParseTest extends TestCase
{
    public function testParseBool(): void
    {
        $type = TypeParser::parse('bool');
        $this->assertEquals('bool', $type->getDescription());
        $this->assertInstanceOf(BoolType::class, $type);
        $this->assertEquals(false, $type->getDefaultValue());

        $type = TypeParser::parse('boolean');
        $this->assertEquals('bool', $type->getDescription());
        $this->assertInstanceOf(BoolType::class, $type);
        $this->assertEquals(false, $type->getDefaultValue());
    }

    public function testParseString(): void
    {
        $type = TypeParser::parse('string');
        $this->assertEquals('string', $type->getDescription());
        $this->assertInstanceOf(StringType::class, $type);
        $this->assertEquals('', $type->getDefaultValue());
    }

    public function testParseNullableString(): void
    {
        $type = TypeParser::parse('?string');
        $this->assertEquals('string|null', $type->getDescription());
        $this->assertInstanceOf(UnionType::class, $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseNullableInt(): void
    {
        $type = TypeParser::parse('?int');
        $this->assertEquals('int|null', $type->getDescription());
        $this->assertInstanceOf(UnionType::class, $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseCallable(): void
    {
        $type = TypeParser::parse('callable');
        $this->assertEquals('callable', $type->getDescription());
        $this->assertInstanceOf(CallableType::class, $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseArrayCallable(): void
    {
        $type = TypeParser::parse('callable[]');
        $this->assertEquals('callable[]', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals([], $type->getDefaultValue());

        $type = TypeParser::parse('array<callable>');
        $this->assertEquals('callable[]', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals([], $type->getDefaultValue());
    }

    public function testParseMixed(): void
    {
        $type = TypeParser::parse('mixed');
        $this->assertEquals('mixed', $type->getDescription());
        $this->assertInstanceOf(MixedType::class, $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseFloat(): void
    {
        $type = Type::parse('float');
        $this->assertEquals('float', $type->getDescription());
        $this->assertInstanceOf(FloatType::class, $type);
        $this->assertEquals(0.0, $type->getDefaultValue());

        $type = Type::parse('real');
        $this->assertEquals('float', $type->getDescription());
        $this->assertInstanceOf(FloatType::class, $type);
        $this->assertEquals(0.0, $type->getDefaultValue());

        $type = Type::parse('double');
        $this->assertEquals('float', $type->getDescription());
        $this->assertInstanceOf(FloatType::class, $type);
        $this->assertEquals(0.0, $type->getDefaultValue());
    }

    public function testParseInt(): void
    {
        $type = Type::parse('int');
        $this->assertEquals('int', $type->getDescription());
        $this->assertInstanceOf(IntType::class, $type);
        $this->assertEquals(0, $type->getDefaultValue());

        $type = Type::parse('integer');
        $this->assertEquals('int', $type->getDescription());
        $this->assertInstanceOf(IntType::class, $type);
        $this->assertEquals(0, $type->getDefaultValue());
    }

    public function testParseResource(): void
    {
        $type = Type::parse('resource');
        $this->assertEquals('resource', $type->getDescription());
        $this->assertInstanceOf(ResourceType::class, $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseIntArray(): void
    {
        $type = Type::parse('int[]');
        $this->assertEquals('int[]', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new IntType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new IntType(), $type->getBasicType());
        $this->assertEquals(new IntType(), $type->getValueType());
        $this->assertEquals(new IntType(), $type->getIndexType());
        $this->assertEquals(1, $type->getDimension());
        $this->assertEquals([], $type->getDefaultValue());
    }

    public function testParseUnionType(): void
    {
        $type = Type::parse('int|string');
        $this->assertEquals('int|string', $type->getDescription());
        $this->assertInstanceOf(UnionType::class, $type);
        $this->assertEquals(new UnionType(new IntType(), new StringType()), $type);
        $this->assertEquals(0, $type->getDefaultValue());

        $type = new UnionType(new StringType(), new IntType());
        $this->assertEquals('', $type->getDefaultValue());
    }

    public function testParseGenericIntArray(): void
    {
        $type = Type::parse('array<int>');
        $this->assertEquals('int[]', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new IntType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new IntType(), $type->getBasicType());
        $this->assertEquals(new IntType(), $type->getValueType());
        $this->assertEquals(new IntType(), $type->getIndexType());
        $this->assertEquals(1, $type->getDimension());
        $this->assertEquals([], $type->getDefaultValue());
    }

    public function testParseGenericIntStringArray(): void
    {
        $type = Type::parse('array<int, string>');
        $this->assertEquals('array<int, string>', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new StringType(), 1, new IntType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new StringType(), $type->getBasicType());
        $this->assertEquals(new StringType(), $type->getValueType());
    }

    public function testParseGenericIntNestedStringArray(): void
    {
        $type = Type::parse('array<int, array<string>>');
        $this->assertEquals('array<int, string[]>', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new ArrayType(new StringType()), 1, new IntType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new StringType(), $type->getBasicType());
        $this->assertEquals(new ArrayType(new StringType()), $type->getValueType());
    }

    public function testParseAssocStringIntArray(): void
    {
        $type = Type::parse('string[int]');
        $this->assertEquals('array<int, string>', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new StringType(), 1, new IntType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new StringType(), $type->getBasicType());
        $this->assertEquals(new StringType(), $type->getValueType());
    }

    public function testParseAssocIntStringArray(): void
    {
        $type = Type::parse('int[string]');
        $this->assertEquals('array<string, int>', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new IntType(), 1, new StringType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new IntType(), $type->getBasicType());
        $this->assertEquals(new IntType(), $type->getValueType());
    }

    public function testParseGenericStringNestedStringIntArrayWithAlternateSyntax(): void
    {
        $type = Type::parse('array<string, string[int]>');
        $this->assertEquals('array<string, array<int, string>>', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new ArrayType(new StringType(), 1, new IntType()), 1, new StringType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new StringType(), $type->getBasicType());
        $this->assertEquals(new ArrayType(new StringType(), 1, new IntType()), $type->getValueType());
    }

    public function testParseGenericStringNestedStringArrayWithAlternateSyntax(): void
    {
        $type = Type::parse('array<int, string[]>');
        $this->assertEquals('array<int, string[]>', $type->getDescription());
        $this->assertInstanceOf(ArrayType::class, $type);
        $this->assertEquals(new ArrayType(new ArrayType(new StringType()), 1, new IntType()), $type);
        /** @var ArrayType $type */
        $this->assertEquals(new StringType(), $type->getBasicType());
        $this->assertEquals(new ArrayType(new StringType(), 1), $type->getValueType());
    }

    public function testParseGenericArrayWithUnionType(): void
    {
        $type = Type::parse('array<string, array<string|int, mixed>>');
        $this->assertEquals('array<string, array<string|int, mixed>>', $type->getDescription());
        $this->assertEquals(
            new ArrayType(
                new ArrayType(
                    new MixedType(),
                    1,
                    new UnionType(
                        new StringType(),
                        new IntType()
                    )
                ),
                1,
                new StringType()
            ),
            $type
        );

        $type = Type::parse('array<string, array<string, mixed|int>>');
        $this->assertEquals('array<string, array<string, mixed|int>>', $type->getDescription());
        $this->assertEquals(
            new ArrayType(
                new ArrayType(
                    new UnionType(
                        new MixedType(),
                        new IntType()
                    ),
                    1,
                    new StringType()
                ),
                1,
                new StringType()
            ),
            $type
        );
    }

    public function testParseObject(): void
    {
        $type = Type::parse('object');
        $this->assertEquals('object', $type->getDescription());
        $this->assertEquals(new ObjectType(), $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseVoid(): void
    {
        $type = Type::parse('void');
        $this->assertEquals('void', $type->getDescription());
        $this->assertEquals(new VoidType(), $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseIterable(): void
    {
        $type = Type::parse('iterable');
        $this->assertEquals('iterable', $type->getDescription());
        $this->assertEquals(new IterableType(), $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseNull(): void
    {
        $type = Type::parse('null');
        $this->assertEquals('null', $type->getDescription());
        $this->assertEquals(new NullType(), $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseMixedNull(): void
    {
        $type = Type::parse('mixed|null');
        $this->assertEquals('mixed|null', $type->getDescription());
        $this->assertEquals(new UnionType(new MixedType(), new NullType()), $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseUnknown(): void
    {
        $type = Type::parse('A');
        $this->assertEquals('A', $type->getDescription());
        $this->assertEquals(new UserDefinedType('A'), $type);
        $this->assertEquals(null, $type->getDefaultValue());
    }

    public function testParseArrayOfUnknownType(): void
    {
        $type = Type::parse('A[]');
        $this->assertEquals('A[]', $type->getDescription());
        $this->assertEquals(new ArrayType(new UserDefinedType('A')), $type);
        $this->assertEquals([], $type->getDefaultValue());
    }
}
