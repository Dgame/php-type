<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class ObjectType
 * @package Dgame\Type
 */
class ObjectType extends Type
{
    /**
     * @var string|null
     */
    private $typeName;

    /**
     * ObjectType constructor.
     *
     * @param string|null $typeName
     */
    public function __construct(string $typeName = null)
    {
        $this->typeName = $typeName;
    }

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
        $visitor->visitObject($this);
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
        return $this->typeName ?? 'object';
    }
}
