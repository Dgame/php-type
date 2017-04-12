<?php

namespace Dgame\Type;

use Exception;
use ReflectionParameter;
use function Dgame\Ensurance\enforce;

/**
 * Class Type
 * @package Dgame\Type
 */
final class Type
{
    const IS_INT      = 1 << 0;
    const IS_NUMERIC  = 1 << 1;
    const IS_FLOAT    = 1 << 2;
    const IS_STRING   = 1 << 3;
    const IS_BOOL     = 1 << 4;
    const IS_ARRAY    = 1 << 5;
    const IS_OBJECT   = 1 << 6;
    const IS_CALLABLE = 1 << 7;
    const IS_NULL     = 1 << 8;

    const TYPE_CALLBACK = [
        self::IS_INT      => 'is_int',
        self::IS_FLOAT    => 'is_float',
        self::IS_NUMERIC  => 'is_numeric',
        self::IS_STRING   => 'is_string',
        self::IS_BOOL     => 'is_bool',
        self::IS_CALLABLE => 'is_callable',
        self::IS_OBJECT   => 'is_object',
        self::IS_ARRAY    => 'is_array',
        self::IS_NULL     => 'is_null'
    ];

    const EXPORT = [
        self::IS_INT      => 'int',
        self::IS_FLOAT    => 'float',
        self::IS_NUMERIC  => 'numeric',
        self::IS_STRING   => 'string',
        self::IS_BOOL     => 'bool',
        self::IS_ARRAY    => 'array',
        self::IS_OBJECT   => 'object',
        self::IS_CALLABLE => 'callable',
        self::IS_NULL     => 'null'
    ];

    const COVARIANCE = [
        self::IS_INT     => [
            self::IS_FLOAT,
            self::IS_NUMERIC,
            self::IS_STRING,
            self::IS_BOOL
        ],
        self::IS_FLOAT   => [
            self::IS_INT,
            self::IS_NUMERIC,
            self::IS_STRING,
            self::IS_BOOL
        ],
        self::IS_BOOL    => [
            self::IS_INT,
            self::IS_NUMERIC,
            self::IS_FLOAT,
            self::IS_STRING
        ],
        self::IS_NUMERIC => [
            self::IS_INT,
            self::IS_FLOAT,
            self::IS_BOOL,
            self::IS_STRING
        ]
    ];

    const BUILT_IN = [
        self::IS_INT,
        self::IS_FLOAT,
        self::IS_NUMERIC,
        self::IS_STRING,
        self::IS_BOOL,
        self::IS_ARRAY,
        self::IS_NULL
    ];

    const DEFAULT_VALUES = [
        self::IS_INT      => 0,
        self::IS_FLOAT    => 0.0,
        self::IS_NUMERIC  => '0',
        self::IS_STRING   => '',
        self::IS_BOOL     => false,
        self::IS_ARRAY    => [],
        self::IS_OBJECT   => null,
        self::IS_CALLABLE => null,
        self::IS_NULL     => null
    ];

    /**
     * @var int
     */
    private $type;

    /**
     * Type constructor.
     *
     * @param int $type
     */
    private function __construct(int $type)
    {
        $this->type = $type;
    }

    /**
     * @param $expression
     *
     * @return Type
     * @throws Exception
     */
    public static function of($expression): self
    {
        foreach (self::TYPE_CALLBACK as $type => $callback) {
            if ($callback($expression)) {
                return new self($type);
            }
        }

        throw new Exception('Unknown expression: ' . $expression);
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return Type
     * @throws Exception
     */
    public static function from(ReflectionParameter $parameter): self
    {
        enforce($parameter->hasType())->orThrow('Parameter has no type');

        if (!$parameter->getType()->isBuiltin()) {
            return new self(self::IS_OBJECT);
        }

        $type = array_search((string) $parameter->getType(), self::EXPORT);
        enforce($type !== false)->orThrow('No type found');

        return new self($type);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public function is(int $type): bool
    {
        return $this->type === $type;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isSame(self $type): bool
    {
        return $type->is($this->type);
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public function isImplicit(int $type): bool
    {
        if ($this->is($type)) {
            return true;
        }

        if (array_key_exists($this->type, self::COVARIANCE)) {
            return in_array($type, self::COVARIANCE[$this->type]);
        }

        return false;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isImplicitSame(self $type): bool
    {
        return $this->isImplicit($type->getType());
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public function accept($expression): bool
    {
        return self::of($expression)->isImplicit($this->type);
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public function equals($expression): bool
    {
        return $this->isSame(self::of($expression));
    }

    /**
     * @return bool
     */
    public function isBuiltIn(): bool
    {
        return in_array($this->type, self::BUILT_IN);
    }

    /**
     * @return bool
     */
    public function isInt(): bool
    {
        return $this->is(self::IS_INT);
    }

    /**
     * @return bool
     */
    public function isFloat(): bool
    {
        return $this->is(self::IS_FLOAT);
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->is(self::IS_NUMERIC);
    }

    /**
     * @return bool
     */
    public function isString(): bool
    {
        return $this->is(self::IS_STRING);
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->is(self::IS_BOOL);
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->is(self::IS_ARRAY);
    }

    /**
     * @return bool
     */
    public function isObject(): bool
    {
        return $this->is(self::IS_OBJECT);
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->is(self::IS_NULL);
    }

    /**
     * @return bool
     */
    public function isCallable(): bool
    {
        return $this->is(self::IS_CALLABLE);
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return self::DEFAULT_VALUES[$this->type];
    }

    /**
     * @return string
     */
    public function export(): string
    {
        return self::EXPORT[$this->type];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->export();
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public static function isEmptyValue($expression): bool
    {
        switch (self::of($expression)->getType()) {
            case self::IS_NULL:
                return true;
            case self::IS_STRING:
            case self::IS_ARRAY:
                return empty($expression);
            default:
                return false;
        }
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public static function isValidValue($expression): bool
    {
        if (self::isEmptyValue($expression)) {
            return false;
        }

        return $expression !== false;
    }
}
