<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class CallableType
 * @package Dgame\Type
 */
final class CallableType extends Type
{
    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return null;
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return is_callable($value);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'callable';
    }
}
