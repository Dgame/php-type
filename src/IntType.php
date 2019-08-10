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
