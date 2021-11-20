<?php

declare(strict_types=1);

namespace Dgame\Type;

use Stringable;

final class StringType extends ScalarType implements Defaultable, Castable
{
    public function cast(mixed $value): string
    {
        return is_scalar($value) || ($value instanceof Stringable) ? (string) $value : '';
    }

    public function getDefaultValue(): string
    {
        return '';
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'string';
    }
}
