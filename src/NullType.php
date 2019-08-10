<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class NullType
 * @package Dgame\Type
 */
final class NullType extends Type
{
    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return null;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isCastableTo(Type $type): bool
    {
        return true;
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'null';
    }
}
