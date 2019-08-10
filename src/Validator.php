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
     * @param mixed $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->type       = TypeOfFactory::expression($expression)->getType();
    }

    /**
     * @param mixed $expression
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
            case TypeOf::IS_NULL:
                return true;
            case TypeOf::IS_STRING:
            case TypeOf::IS_ARRAY:
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
