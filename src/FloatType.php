<?php

declare(strict_types=1);

namespace Dgame\Type;

final class FloatType extends NumberType
{
    public function isAssignable(Type $other): bool
    {
        return $other instanceof $this || $other instanceof IntType;
    }

    public function cast(mixed $value): float
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }

    public function getDefaultValue(): float
    {
        return 0.0;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'float';
    }
}
