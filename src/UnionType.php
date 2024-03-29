<?php

declare(strict_types=1);

namespace Dgame\Type;

use BadMethodCallException;

final class UnionType extends Type
{
    /** @var Type[] */
    private array $types = [];

    public function __construct(Type $type1, Type $type2, Type ...$types)
    {
        array_push($this->types, $type1, $type2, ...$types);
    }

    /**
     * @return Type[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function isAssignable(Type $other): bool
    {
        foreach ($this->types as $type) {
            if ($type->isAssignable($other)) {
                return true;
            }
        }

        return false;
    }

    public function hasDefaultValue(): bool
    {
        if ($this->allowsNull()) {
            return true;
        }

        foreach ($this->types as $type) {
            if ($type instanceof Defaultable) {
                return true;
            }
        }

        return false;
    }

    public function isCastable(): bool
    {
        foreach ($this->types as $type) {
            if ($type instanceof Castable) {
                return true;
            }
        }

        return false;
    }

    public function allowsNull(): bool
    {
        foreach ($this->types as $type) {
            if ($type instanceof NullType) {
                return true;
            }
        }

        return false;
    }

    public function getDefaultValue(): mixed
    {
        if ($this->allowsNull()) {
            return null;
        }

        foreach ($this->types as $type) {
            if ($type instanceof Defaultable) {
                return $type->getDefaultValue();
            }
        }

        throw new BadMethodCallException(__METHOD__);
    }

    public function cast(mixed $value): mixed
    {
        foreach ($this->types as $type) {
            if ($type instanceof Castable) {
                return $type->cast($value);
            }
        }

        throw new BadMethodCallException(__METHOD__);
    }

    public function isBuiltIn(): bool
    {
        foreach ($this->types as $type) {
            if (!$type->isBuiltIn()) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        $output = [];
        foreach ($this->types as $type) {
            $output[] = (string) $type;
        }

        return implode('|', $output);
    }
}
