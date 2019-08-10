<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class StringType
 * @package Dgame\Type
 */
final class StringType extends Type
{
    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return '';
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isCastableTo(Type $type): bool
    {
        return $type->acceptValue($this->getDefaultValue(), false);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return $strict ? is_string($value) : true;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'string';
    }
}
