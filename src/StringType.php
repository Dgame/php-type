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
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitString($this);
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
