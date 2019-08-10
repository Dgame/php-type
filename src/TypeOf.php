<?php

declare(strict_types=1);

namespace Dgame\Type;

use RuntimeException;

/**
 * Class TypeOf
 * @package Dgame\Type
 */
final class TypeOf
{
    public const IS_INT      = 1 << 0;
    public const IS_NUMERIC  = 1 << 1;
    public const IS_FLOAT    = 1 << 2;
    public const IS_STRING   = 1 << 3;
    public const IS_BOOL     = 1 << 4;
    public const IS_ARRAY    = 1 << 5;
    public const IS_OBJECT   = 1 << 6;
    public const IS_CALLABLE = 1 << 7;
    public const IS_NULL     = 1 << 8;
    public const IS_MIXED    = 1 << 9;
    public const NONE        = 1 << 10;

    public const TYPE_CALLBACK = [
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

    private const EXPORT = [
        self::IS_INT      => 'int',
        self::IS_FLOAT    => 'float',
        self::IS_NUMERIC  => 'numeric',
        self::IS_STRING   => 'string',
        self::IS_BOOL     => 'bool',
        self::IS_ARRAY    => 'array',
        self::IS_OBJECT   => 'object',
        self::IS_CALLABLE => 'callable',
        self::IS_NULL     => 'null',
        self::IS_MIXED    => 'mixed'
    ];

    private const ALIAS = [
        'double'  => self::IS_FLOAT,
        'real'    => self::IS_FLOAT,
        'integer' => self::IS_INT,
        'long'    => self::IS_INT,
        'boolean' => self::IS_BOOL
    ];

    private const COVARIANCE = [
        self::IS_INT      => [
            self::IS_FLOAT,
            self::IS_NUMERIC,
            self::IS_STRING,
            self::IS_BOOL,
            self::IS_MIXED
        ],
        self::IS_FLOAT    => [
            self::IS_INT,
            self::IS_NUMERIC,
            self::IS_STRING,
            self::IS_BOOL,
            self::IS_MIXED
        ],
        self::IS_STRING   => [
            self::IS_MIXED
        ],
        self::IS_BOOL     => [
            self::IS_INT,
            self::IS_NUMERIC,
            self::IS_FLOAT,
            self::IS_STRING,
            self::IS_MIXED
        ],
        self::IS_NUMERIC  => [
            self::IS_INT,
            self::IS_FLOAT,
            self::IS_BOOL,
            self::IS_STRING,
            self::IS_MIXED
        ],
        self::IS_ARRAY    => [
            self::IS_MIXED
        ],
        self::IS_OBJECT   => [
            self::IS_MIXED
        ],
        self::IS_CALLABLE => [
            self::IS_MIXED
        ],
        self::IS_NULL     => [
            self::IS_MIXED
        ],
        self::IS_MIXED    => [
            self::IS_INT,
            self::IS_FLOAT,
            self::IS_NUMERIC,
            self::IS_STRING,
            self::IS_BOOL,
            self::IS_CALLABLE,
            self::IS_OBJECT,
            self::IS_ARRAY,
            self::IS_NULL,
            self::IS_MIXED
        ]
    ];

    private const BUILT_IN = [
        self::IS_INT,
        self::IS_FLOAT,
        self::IS_NUMERIC,
        self::IS_STRING,
        self::IS_BOOL,
        self::IS_ARRAY,
        self::IS_NULL,
        self::IS_MIXED
    ];

    private const DEFAULT_VALUES = [
        self::IS_INT      => 0,
        self::IS_FLOAT    => 0.0,
        self::IS_NUMERIC  => '0',
        self::IS_STRING   => '',
        self::IS_BOOL     => false,
        self::IS_ARRAY    => [],
        self::IS_OBJECT   => null,
        self::IS_CALLABLE => null,
        self::IS_NULL     => null,
        self::IS_MIXED    => null
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
    public function __construct(int $type)
    {
        $this->type = $type;
    }

    /**
     * @param string $type
     *
     * @return TypeOf
     */
    public static function import(string $type): self
    {
        $alias = self::alias($type);
        if ($alias === self::NONE) {
            throw new RuntimeException('Could not import ' . $type);
        }

        return new self($alias);
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
     * @param TypeOf $type
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
            return in_array($type, self::COVARIANCE[$this->type], true);
        }

        return false;
    }

    /**
     * @param TypeOf $type
     *
     * @return bool
     */
    public function isImplicitSame(self $type): bool
    {
        return $this->isImplicit($type->getType());
    }

    /**
     * @param mixed $expression
     *
     * @return bool
     */
    public function accept($expression): bool
    {
        return TypeOfFactory::expression($expression)->isImplicit($this->type);
    }

    /**
     * @param mixed $expression
     *
     * @return bool
     */
    public function equals($expression): bool
    {
        return $this->isSame(TypeOfFactory::expression($expression));
    }

    /**
     * @return bool
     */
    public function isBuiltIn(): bool
    {
        return in_array($this->type, self::BUILT_IN, true);
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
    public function isMixed(): bool
    {
        return $this->is(self::IS_MIXED);
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
     * @param string $type
     *
     * @return int
     */
    public static function alias(string $type): int
    {
        $type = trim($type);
        if (array_key_exists($type, self::ALIAS)) {
            return self::ALIAS[$type];
        }

        $key = array_search($type, self::EXPORT, true);

        return $key !== false ? $key : self::NONE;
    }
}
