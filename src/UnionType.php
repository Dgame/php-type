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
        if ($type->isNullable()) {
            $this->setIsNullable(true);
        }

        $this->types[$type->getDescription()] = $type;
    }

    /**
     * @return Type
     */
    public function unwrap(): Type
    {
        if (empty($this->types)) {
            return new VoidType();
        }

        if (count($this->types) === 1) {
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
     * @param Type $other
     *
     * @return bool
     */
    public function acceptType(Type $other): bool
    {
        foreach ($this->types as $type) {
            if ($type->acceptType($other)) {
                return true;
            }
        }

        return false;
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
        return implode('|', array_map(static function (Type $type): string {
            return $type->getDescription();
        }, $this->types));
    }
}
