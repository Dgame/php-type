<?php

namespace Dgame\Test\Type;

use Dgame\Type\ArrayType;
use Dgame\Type\IntType;
use Dgame\Type\StringType;
use Dgame\Type\TypeResolver;
use Dgame\Type\UnionType;
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
        $this->assertEquals(['int'], $resolver->getNames());
    }

    public function testArrayStringType(): void
    {
        $type     = new ArrayType(new StringType());
        $resolver = new TypeResolver($type);
        $this->assertFalse($resolver->isIntType());
        $this->assertTrue($resolver->isArrayType());
        $this->assertNotNull($resolver->getArrayType());
        $this->assertNull($resolver->getIntType());
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
        $this->assertEquals(['string', 'int'], $resolver->getNames());
    }
}
