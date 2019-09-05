<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class TypeResolver
 * @package Dgame\Type
 */
final class TypeResolver implements TypeVisitorInterface
{
    /**
     * @var GenericType|null
     */
    private $genericType;
    /**
     * @var ArrayType|null
     */
    private $arrayType;
    /**
     * @var BoolType|null
     */
    private $boolType;
    /**
     * @var CallableType|null
     */
    private $callableType;
    /**
     * @var FloatType|null
     */
    private $floatType;
    /**
     * @var IntType|null
     */
    private $intType;
    /**
     * @var IterableType|null
     */
    private $iterableType;
    /**
     * @var MixedType|null
     */
    private $mixedType;
    /**
     * @var NullType|null
     */
    private $nullType;
    /**
     * @var ObjectType|null
     */
    private $objectType;
    /**
     * @var ResourceType|null
     */
    private $resourceType;
    /**
     * @var StringType|null
     */
    private $stringType;
    /**
     * @var UnionType|null
     */
    private $unionType;
    /**
     * @var UserDefinedType|null
     */
    private $userDefinedType;
    /**
     * @var VoidType|null
     */
    private $voidType;
    /**
     * @var string[]
     */
    private $names = [];

    /**
     * TypeResolver constructor.
     *
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        $type->accept($this);
    }

    /**
     * @param Type $type
     */
    private function appendNames(Type $type): void
    {
        array_push($this->names, ...explode('|', $type->getDescription()));
        $this->names = array_unique($this->names);
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @param GenericType $type
     */
    public function visitGeneric(GenericType $type): void
    {
        $this->genericType = $type;
        $this->appendNames($type);
    }

    /**
     * @param ArrayType $type
     */
    public function visitArray(ArrayType $type): void
    {
        $this->arrayType = $type;
        $this->appendNames($type);
    }

    /**
     * @param BoolType $type
     */
    public function visitBool(BoolType $type): void
    {
        $this->boolType = $type;
        $this->appendNames($type);
    }

    /**
     * @param CallableType $type
     */
    public function visitCallable(CallableType $type): void
    {
        $this->callableType = $type;
        $this->appendNames($type);
    }

    /**
     * @param FloatType $type
     */
    public function visitFloat(FloatType $type): void
    {
        $this->floatType = $type;
        $this->appendNames($type);
    }

    /**
     * @param IntType $type
     */
    public function visitInt(IntType $type): void
    {
        $this->intType = $type;
        $this->appendNames($type);
    }

    /**
     * @param IterableType $type
     */
    public function visitIterable(IterableType $type): void
    {
        $this->iterableType = $type;
        $this->appendNames($type);
    }

    /**
     * @param MixedType $type
     */
    public function visitMixed(MixedType $type): void
    {
        $this->mixedType = $type;
        $this->appendNames($type);
    }

    /**
     * @param NullType $type
     */
    public function visitNull(NullType $type): void
    {
        $this->nullType = $type;
        $this->appendNames($type);
    }

    /**
     * @param ObjectType $type
     */
    public function visitObject(ObjectType $type): void
    {
        $this->objectType = $type;
        $this->appendNames($type);
    }

    /**
     * @param ResourceType $type
     */
    public function visitResource(ResourceType $type): void
    {
        $this->resourceType = $type;
        $this->appendNames($type);
    }

    /**
     * @param StringType $type
     */
    public function visitString(StringType $type): void
    {
        $this->stringType = $type;
        $this->appendNames($type);
    }

    /**
     * @param UnionType $type
     */
    public function visitUnion(UnionType $type): void
    {
        $this->unionType = $type;
        $this->appendNames($type);
    }

    /**
     * @param UserDefinedType $type
     */
    public function visitUserDefined(UserDefinedType $type): void
    {
        $this->userDefinedType = $type;
        $this->appendNames($type);
    }

    /**
     * @param VoidType $type
     */
    public function visitVoid(VoidType $type): void
    {
        $this->voidType = $type;
        $this->appendNames($type);
    }

    /**
     * @return bool
     */
    public function isGenericType(): bool
    {
        return $this->genericType !== null;
    }

    /**
     * @return bool
     */
    public function isArrayType(): bool
    {
        return $this->arrayType !== null;
    }

    /**
     * @return bool
     */
    public function isBoolType(): bool
    {
        return $this->boolType !== null;
    }

    /**
     * @return bool
     */
    public function isCallableType(): bool
    {
        return $this->callableType !== null;
    }

    /**
     * @return bool
     */
    public function isFloatType(): bool
    {
        return $this->floatType !== null;
    }

    /**
     * @return bool
     */
    public function isIntType(): bool
    {
        return $this->intType !== null;
    }

    /**
     * @return bool
     */
    public function isIterableType(): bool
    {
        return $this->iterableType !== null;
    }

    /**
     * @return bool
     */
    public function isMixedType(): bool
    {
        return $this->mixedType !== null;
    }

    /**
     * @return bool
     */
    public function isNullType(): bool
    {
        return $this->nullType !== null;
    }

    /**
     * @return bool
     */
    public function isObjectType(): bool
    {
        return $this->objectType !== null;
    }

    /**
     * @return bool
     */
    public function isResourceType(): bool
    {
        return $this->resourceType !== null;
    }

    /**
     * @return bool
     */
    public function isStringType(): bool
    {
        return $this->stringType !== null;
    }

    /**
     * @return bool
     */
    public function isUnionType(): bool
    {
        return $this->unionType !== null;
    }

    /**
     * @return bool
     */
    public function isUserDefinedType(): bool
    {
        return $this->userDefinedType !== null;
    }

    /**
     * @return bool
     */
    public function isVoidType(): bool
    {
        return $this->voidType !== null;
    }

    /**
     * @return ArrayType|null
     */
    public function getArrayType(): ?ArrayType
    {
        return $this->arrayType;
    }

    /**
     * @return BoolType|null
     */
    public function getBoolType(): ?BoolType
    {
        return $this->boolType;
    }

    /**
     * @return CallableType|null
     */
    public function getCallableType(): ?CallableType
    {
        return $this->callableType;
    }

    /**
     * @return FloatType|null
     */
    public function getFloatType(): ?FloatType
    {
        return $this->floatType;
    }

    /**
     * @return IntType|null
     */
    public function getIntType(): ?IntType
    {
        return $this->intType;
    }

    /**
     * @return IterableType|null
     */
    public function getIterableType(): ?IterableType
    {
        return $this->iterableType;
    }

    /**
     * @return MixedType|null
     */
    public function getMixedType(): ?MixedType
    {
        return $this->mixedType;
    }

    /**
     * @return NullType|null
     */
    public function getNullType(): ?NullType
    {
        return $this->nullType;
    }

    /**
     * @return ObjectType|null
     */
    public function getObjectType(): ?ObjectType
    {
        return $this->objectType;
    }

    /**
     * @return ResourceType|null
     */
    public function getResourceType(): ?ResourceType
    {
        return $this->resourceType;
    }

    /**
     * @return StringType|null
     */
    public function getStringType(): ?StringType
    {
        return $this->stringType;
    }

    /**
     * @return UnionType|null
     */
    public function getUnionType(): ?UnionType
    {
        return $this->unionType;
    }

    /**
     * @return UserDefinedType|null
     */
    public function getUserDefinedType(): ?UserDefinedType
    {
        return $this->userDefinedType;
    }

    /**
     * @return VoidType|null
     */
    public function getVoidType(): ?VoidType
    {
        return $this->voidType;
    }
}
