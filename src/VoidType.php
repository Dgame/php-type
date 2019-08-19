<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class VoidType
 * @package Dgame\Type
 */
final class VoidType extends Type
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
        $visitor->visitVoid($this);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'void';
    }
}
