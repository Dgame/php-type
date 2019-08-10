<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class Type
 * @package Dgame\Type
 */
abstract class Type
{
    private const TYPE_CALLBACKS = [
        'is_int'      => 'int',
        'is_float'    => 'float',
        'is_numeric'  => null,
        'is_string'   => 'string',
        'is_bool'     => 'bool',
        'is_callable' => 'callable',
        'is_object'   => 'object',
        'is_resource' => 'resource',
        'is_array'    => 'array',
        'is_iterable' => 'iterable',
        'is_null'     => 'null'
    ];

    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * @param mixed $value
     *
     * @return Type
     */
    public static function fromValue($value): self
    {
        /**
         * @var callable  $callback
         * @var  string[] $types
         */
        foreach (self::TYPE_CALLBACKS as $callback => $type) {
            if ($callback($value)) {
                return $type !== null ? self::parse($type) : self::fromValue(self::interpretValue($value));
            }
        }

        return new MixedType();
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function interpretValue($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param string $typeName
     *
     * @return Type
     */
    public static function parse(string $typeName): self
    {
        $union = new UnionType();
        foreach (explode('|', $typeName) as $type) {
            $type = trim($type);
            if (strpos($type, '?') === 0) {
                $union->setIsNullable(true);
                $type = substr($type, 1);
            }

            $union->appendType(self::fromName($type));
        }

        return $union->unwrap();
    }

    /**
     * @param string $typeName
     *
     * @return Type
     */
    public static function fromName(string $typeName): self
    {
        $basicType = preg_replace('/\[.*?\]$/S', '', trim($typeName)) ?? '';
        switch (trim($basicType, '?')) {
            case 'callable':
                $type = new CallableType();
                break;
            case 'iterable':
                $type = new IterableType();
                break;
            case 'null':
                $type = new NullType();
                break;
            case 'object':
            case 'static':
            case 'self':
            case 'parent':
                $type = new ObjectType();
                break;
            case 'void':
                $type = new VoidType();
                break;
            case 'array':
                $type = new ArrayType();
                break;
            case 'bool':
            case 'boolean':
                $type = new BoolType();
                break;
            case 'float':
            case 'double':
            case 'real':
                $type = new FloatType();
                break;
            case 'int':
            case 'integer':
                $type = new IntType();
                break;
            case 'resource':
            case 'resource (closed)':
                $type = new ResourceType();
                break;
            case 'string':
                $type = new StringType();
                break;
            case 'mixed':
                $type = new MixedType();
                break;
            default:
                $type = ArrayType::parseGeneric($typeName) ?? new UnknownType($basicType);
                break;
        }

        return self::buildArray($basicType, $typeName, $type);
    }

    /**
     * @param string $basicType
     * @param string $typeName
     * @param Type   $type
     *
     * @return Type
     */
    private static function buildArray(string $basicType, string $typeName, self $type): self
    {
        $offset = strlen($basicType);
        while (preg_match('/\s*\[\s*(?<index>\w*)\s*\]\s*/S', $typeName, $matches, 0, $offset) === 1) {
            $offset += strlen($matches[0]);
            $indexType = !empty($matches['index']) ? self::parse($matches['index']) : null;
            $type      = new ArrayType($type, 1, $indexType);
        }

        return $type;
    }

    /**
     * @param bool $nullable
     */
    public function setIsNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function acceptType(self $type): bool
    {
        return $type instanceof $this;
    }

    /**
     * @return mixed
     */
    abstract public function getDefaultValue();

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    abstract public function acceptValue($value, bool $strict): bool;

    /**
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * @return ArrayType
     */
    public function toArray(): ArrayType
    {
        return new ArrayType($this);
    }

    /**
     * @return ArrayType|null
     */
    public function isArray(): ?ArrayType
    {
        return null;
    }

    /**
     * @return ObjectType|null
     */
    public function isObject(): ?ObjectType
    {
        return null;
    }

    /**
     * @return UnknownType|null
     */
    public function isUnknown(): ?UnknownType
    {
        return null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDescription();
    }
}
