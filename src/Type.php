<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class Type
 * @package Dgame\Type
 */
abstract class Type
{
    /**
     * @param mixed $value
     *
     * @return Type
     */
    public static function fromValue($value): self
    {
        return TypeParser::fromValue($value);
    }

    /**
     * @param string $typeName
     *
     * @return Type
     */
    public static function parse(string $typeName): self
    {
        return TypeParser::parse($typeName);
    }

    /**
     * @return mixed
     */
    abstract public function getDefaultValue();

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    abstract public function acceptValue($value, bool $strict): bool;

    /**
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * @param TypeVisitorInterface $visitor
     */
    abstract public function accept(TypeVisitorInterface $visitor): void;

    /**
     * @return ArrayType
     */
    final public function toArray(): ArrayType
    {
        return new ArrayType($this);
    }

    /**
     * @return UnionType
     */
    public function asNullable(): UnionType
    {
        return new UnionType($this, new NullType());
    }

    /**
     * @return string
     */
    final public function __toString(): string
    {
        return $this->getDescription();
    }
}
