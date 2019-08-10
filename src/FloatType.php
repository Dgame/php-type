<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class FloatType
 * @package Dgame\Type
 */
final class FloatType extends Type
{
    /**
     * @return float
     */
    public function getDefaultValue(): float
    {
        return 0.0;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isCastableTo(Type $type): bool
    {
        return $this->acceptValue($type->getDefaultValue(), false);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return $strict ? is_float($value) : is_numeric($value);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'float';
    }
}
