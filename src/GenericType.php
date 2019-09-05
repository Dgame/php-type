<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class GenericType
 * @package Dgame\Type
 */
final class GenericType extends Type
{
    /**
     * @var Type
     */
    private $baseType;
    /**
     * @var Type[]
     */
    private $genericTypes;

    /**
     * GenericType constructor.
     *
     * @param Type $baseType
     * @param Type ...$genericTypes
     */
    public function __construct(Type $baseType, Type ...$genericTypes)
    {
        $this->baseType     = $baseType;
        $this->genericTypes = $genericTypes;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->baseType->getDefaultValue();
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        return $this->baseType->acceptValue($value, $strict);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return sprintf('%s<%s>', (string) $this->baseType, implode(', ', array_map(static function (Type $type): string {
            return (string) $type;
        }, $this->genericTypes)));
    }

    /**
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitGeneric($this);
    }
}
