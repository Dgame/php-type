<?php

namespace Dgame\Type;

/**
 * Class Type
 * @package Dgame\Type
 */
final class Type
{
    const TYPE_BUILTIN = [
        'int',
        'float',
        'numeric',
        'string',
        'bool',
        'array'
    ];

    const TYPE_IMPLICIT = [
        'int'     => ['float', 'numeric', 'string'],
        'float'   => ['int', 'numeric', 'string'],
        'numeric' => ['int', 'float', 'bool', 'string'],
        'bool'    => ['int', 'float', 'numeric', 'string'],
    ];

    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $name;

    /**
     * Type constructor.
     *
     * @param TypeInfo $typeInfo
     */
    public function __construct(TypeInfo $typeInfo)
    {
        $this->type = $typeInfo->getType();
        $this->name = $typeInfo->getName();
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

    /**
     * @return bool
     */
    public function isBuiltin(): bool
    {
        return in_array($this->type, self::TYPE_BUILTIN);
    }

    /**
     * @return bool
     */
    public function isInt(): bool
    {
        return $this->is('int');
    }

    /**
     * @return bool
     */
    public function isFloat(): bool
    {
        return $this->is('float');
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->is('numeric');
    }

    /**
     * @return bool
     */
    public function isString(): bool
    {
        return $this->is('string');
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->is('bool');
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->is('array');
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->is('null');
    }

    /**
     * @return bool
     */
    public function isObject(): bool
    {
        return $this->is('object');
    }

    /**
     * @return bool
     */
    public function isResource(): bool
    {
        return $this->is('resource');
    }

    /**
     * @return bool
     */
    public function isCallback(): bool
    {
        return $this->is('callable');
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function is(string $type): bool
    {
        return $this->type === $type || $this->name === $type;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isImplicit(string $type): bool
    {
        if ($this->is($type)) {
            return true;
        }

        if (array_key_exists($this->type, self::TYPE_IMPLICIT)) {
            return in_array($type, self::TYPE_IMPLICIT[$this->type]);
        }

        return false;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isSameAs(Type $type): bool
    {
        return $type->getType() === $this->type;
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isConvertibleTo(Type $type): bool
    {
        if ($this->isSameAs($type)) {
            return true;
        }

        if (array_key_exists($this->type, self::TYPE_IMPLICIT)) {
            return in_array($type->getType(), self::TYPE_IMPLICIT[$this->type]);
        }

        return false;
    }
}
