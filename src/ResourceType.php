<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class ResourceType
 * @package Dgame\Type
 */
final class ResourceType extends Type
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
        return is_resource($value);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'resource';
    }
}
