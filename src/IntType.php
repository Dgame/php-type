<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class IntType
 * @package Dgame\Type
 */
final class IntType extends Type
{
    /**
     * @return int
     */
    public function getDefaultValue(): int
    {
        return 0;
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return $strict ? is_int($value) : is_numeric($value);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'int';
    }
}
