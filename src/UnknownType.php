<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class UnknownType
 * @package Dgame\Type
 */
final class UnknownType extends Type
{
    /**
     * @var string|null
     */
    private $typeName;

    /**
     * MixedType constructor.
     *
     * @param string|null $typeName
     */
    public function __construct(string $typeName = null)
    {
        $this->typeName = $typeName;
    }

    /**
     * @return UnknownType|null
     */
    public function isUnknown(): ?self
    {
        return $this;
    }

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
        return false;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->typeName ?? '';
    }
}
