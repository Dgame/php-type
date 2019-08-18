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
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitFloat($this);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return $strict ? (is_float($value) || is_int($value)) : is_numeric($value);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'float';
    }
}
