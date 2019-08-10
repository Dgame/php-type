<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class BoolType
 * @package Dgame\Type
 */
final class BoolType extends Type
{
    /**
     * @return bool
     */
    public function getDefaultValue(): bool
    {
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
        return $strict ? is_bool($value) : filter_var($value, FILTER_VALIDATE_BOOLEAN) !== null;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'bool';
    }
}
