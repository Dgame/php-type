<?php

declare(strict_types=1);

namespace Dgame\Type;

class IntType extends NumberType
{
    public function cast(mixed $value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }

    public function getDefaultValue(): int
    {
        return 0;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'int';
    }
}
