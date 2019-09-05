<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Interface TypeVisitorInterface
 * @package Dgame\Type
 */
interface TypeVisitorInterface
{
    /**
     * @param GenericType $type
     */
    public function visitGeneric(GenericType $type): void;

    /**
     * @param ArrayType $type
     */
    public function visitArray(ArrayType $type): void;

    /**
     * @param BoolType $type
     */
    public function visitBool(BoolType $type): void;

    /**
     * @param CallableType $type
     */
    public function visitCallable(CallableType $type): void;

    /**
     * @param FloatType $type
     */
    public function visitFloat(FloatType $type): void;

    /**
     * @param IntType $type
     */
    public function visitInt(IntType $type): void;

    /**
     * @param IterableType $type
     */
    public function visitIterable(IterableType $type): void;

    /**
     * @param MixedType $type
     */
    public function visitMixed(MixedType $type): void;

    /**
     * @param NullType $type
     */
    public function visitNull(NullType $type): void;

    /**
     * @param ObjectType $type
     */
    public function visitObject(ObjectType $type): void;

    /**
     * @param ResourceType $type
     */
    public function visitResource(ResourceType $type): void;

    /**
     * @param StringType $type
     */
    public function visitString(StringType $type): void;

    /**
     * @param UnionType $type
     */
    public function visitUnion(UnionType $type): void;

    /**
     * @param UserDefinedType $type
     */
    public function visitUserDefined(UserDefinedType $type): void;

    /**
     * @param VoidType $type
     */
    public function visitVoid(VoidType $type): void;
}
