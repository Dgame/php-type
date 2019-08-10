<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class IterableType
 * @package Dgame\Type
 */
final class IterableType extends Type
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
        return $this->acceptType($type);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return is_iterable($value);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'iterable';
    }
}
