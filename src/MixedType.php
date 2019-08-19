<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class MixedType
 * @package Dgame\Type
 */
final class MixedType extends Type
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
        $visitor->visitMixed($this);
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
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
        return 'mixed';
    }
}
