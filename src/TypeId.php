<?php

namespace Dgame\Type;

/**
 * Class TypeInfo
 * @package Dgame\Type
 */
class TypeInfo
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $name;

    /**
     * TypeId constructor.
     *
     * @param string      $type
     * @param string|null $name
     */
    public function __construct(string $type, string $name = null)
    {
        $this->type = $type;
        $this->name = $name ?? $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}