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
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitCallable($this);
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
