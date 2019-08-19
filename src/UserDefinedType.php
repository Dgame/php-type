<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class UserDefinedType
 * @package Dgame\Type
 */
final class UserDefinedType extends ObjectType
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
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitUserDefined($this);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->typeName ?? parent::getDescription();
    }
}
