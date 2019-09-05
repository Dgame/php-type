<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class UnionType
 * @package Dgame\Type
 */
final class UnionType extends Type
{
    /**
     * @var Type[]
     */
    private $types;

    /**
     * UnionType constructor.
     *
     * @param Type ...$types
     */
    public function __construct(Type ...$types)
    {
        foreach ($types as $type) {
            $this->appendType($type);
        }
    }

    /**
     * @param Type $type
     */
    public function appendType(Type $type): void
    {
        $this->types[$type->getDescription()] = $type;
    }

    /**
     * @return bool
     */
    public function isSingleType(): bool
    {
        return count($this->types) === 1;
    }

    /**
     * @return Type
     */
    public function unwrap(): Type
    {
        if (empty($this->types)) {
            return new VoidType();
        }

        if ($this->isSingleType()) {
            return array_pop($this->types);
        }

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return empty($this->types) ? null : reset($this->types)->getDefaultValue();
    }

    /**
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitUnion($this);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        foreach ($this->types as $type) {
            if ($type->acceptValue($value, $strict)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return implode('|', array_keys($this->types));
    }

    /**
     * @return UnionType
     */
    public function asNullable(): self
    {
        $this->appendType(new NullType());

        return $this;
    }
}
