<?php

namespace Dgame\Type;

/**
 * Class Validator
 * @package Dgame\Type
 */
final class Validator
{
    /**
     * @var int
     */
    private $type;
    /**
     * @var mixed
     */
    private $expression;

    /**
     * Validator constructor.
     *
     * @param $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->type       = Type::of($expression)->getType();
    }

    /**
     * @param $expression
     *
     * @return Validator
     */
    public static function verify($expression): self
    {
        return new self($expression);
    }

    /**
     * @return bool
     */
    public function isEmptyValue(): bool
    {
        switch ($this->type) {
            case Type::IS_NULL:
                return true;
            case Type::IS_STRING:
            case Type::IS_ARRAY:
                return empty($this->expression);
            default:
                return false;
        }
    }

    /**
     * @return bool
     */
    public function isValidValue(): bool
    {
        if ($this->isEmptyValue()) {
            return false;
        }

        return $this->expression !== false;
    }
}