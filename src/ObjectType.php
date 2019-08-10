<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class ObjectType
 * @package Dgame\Type
 */
final class ObjectType extends Type
{
    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return null;
    }

    /**
     * @return ObjectType|null
     */
    public function isObject(): ?ObjectType
    {
        return $this;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isCastableTo(Type $type): bool
    {
        return $this->acceptType($type) || $type->isArray() !== null;
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return is_object($value) || $value === null;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'object';
    }
}
