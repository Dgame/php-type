<?php

namespace Dgame\Test\Type;

use Dgame\Type\ArrayType;
use Dgame\Type\BoolType;
use Dgame\Type\FloatType;
use Dgame\Type\IntType;
use Dgame\Type\MixedType;
use Dgame\Type\NullType;
use Dgame\Type\ObjectType;
use Dgame\Type\StringType;
use Dgame\Type\TypeResolver;
use Dgame\Type\UnionType;
use Dgame\Type\VoidType;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    public function testIntType(): void
    {
        $type     = new IntType();
        $resolver = new TypeResolver($type);
        $this->assertTrue($resolver->isIntType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNotNull($resolver->getIntType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['int'], $resolver->getNames());
    }

    public function testFloatType(): void
    {
        $type     = new FloatType();
        $resolver = new TypeResolver($type);
        $this->assertTrue($resolver->isFloatType());
        $this->assertNotNull($resolver->getFloatType());
        $this->assertFalse($resolver->isIntType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNull($resolver->getIntType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['float'], $resolver->getNames());
    }

    public function testBoolType(): void
    {
        $type     = new BoolType();
        $resolver = new TypeResolver($type);
        $this->assertTrue($resolver->isBoolType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNotNull($resolver->getBoolType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['bool'], $resolver->getNames());
    }

    public function testNullType(): void
    {
        $type     = new NullType();
        $resolver = new TypeResolver($type);
        $this->assertTrue($resolver->isNullType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNotNull($resolver->getNullType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['null'], $resolver->getNames());
    }

    public function testVoidType(): void
    {
        $type     = new VoidType();
        $resolver = new TypeResolver($type);
        $this->assertTrue($resolver->isVoidType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNotNull($resolver->getVoidType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['void'], $resolver->getNames());
    }

    public function testMixedType(): void
    {
        $type     = new MixedType();
        $resolver = new TypeResolver($type);
        $this->assertTrue($resolver->isMixedType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNotNull($resolver->getMixedType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['mixed'], $resolver->getNames());
    }

    public function testArrayStringType(): void
    {
        $type     = new ArrayType(new StringType());
        $resolver = new TypeResolver($type);
        $this->assertFalse($resolver->isIntType());
        $this->assertTrue($resolver->isArrayType());
        $this->assertNotNull($resolver->getArrayType());
        $this->assertNull($resolver->getIntType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['string[]'], $resolver->getNames());
    }

    public function testUnionType(): void
    {
        $type     = new UnionType(new StringType(), new IntType());
        $resolver = new TypeResolver($type);
        $this->assertFalse($resolver->isIntType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNull($resolver->getIntType());
        $this->assertTrue($resolver->isUnionType());
        $this->assertNotNull($resolver->getUnionType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertFalse($resolver->isObjectType());
        $this->assertNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['string', 'int'], $resolver->getNames());
    }

    public function testObjectType(): void
    {
        $type     = new ObjectType();
        $resolver = new TypeResolver($type);
        $this->assertFalse($resolver->isIntType());
        $this->assertFalse($resolver->isArrayType());
        $this->assertNull($resolver->getArrayType());
        $this->assertNull($resolver->getIntType());
        $this->assertFalse($resolver->isUnionType());
        $this->assertNull($resolver->getUnionType());
        $this->assertFalse($resolver->isCallableType());
        $this->assertNull($resolver->getCallableType());
        $this->assertFalse($resolver->isResourceType());
        $this->assertNull($resolver->getResourceType());
        $this->assertTrue($resolver->isObjectType());
        $this->assertNotNull($resolver->getObjectType());
        $this->assertFalse($resolver->isIterableType());
        $this->assertNull($resolver->getIterableType());
        $this->assertEquals(['object'], $resolver->getNames());
    }
}
