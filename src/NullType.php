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
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitNull($this);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return $strict ? $value === null : $value == null;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'null';
    }
}
