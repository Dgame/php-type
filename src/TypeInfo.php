<?php

namespace Dgame\Type;

/**
 * Class TypeInfo
 * @package Dgame\Type
 */
final class TypeInfo
{
    const TYPE_ALIAS = [
        'integer' => 'int',
        'double'  => 'float',
        'real'    => 'float',
        'boolean' => 'bool'
    ];

    const TYPE_CALLBACKS = [
        'int'      => 'is_int',
        'float'    => 'is_float',
        'numeric'  => 'is_numeric',
        'string'   => 'is_string',
        'bool'     => 'is_bool',
        'array'    => 'is_array',
        'null'     => 'is_null',
        'object'   => 'is_object',
        'resource' => 'is_resource',
        'callable' => 'is_callable',
    ];

    const TYPE_NAME = [
        'object'   => 'get_class',
        'resource' => 'get_resource_type'
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
     * Typeof constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        foreach (self::TYPE_CALLBACKS as $type => $callback) {
            if ($callback($value)) {
                $this->type = $type;
                $this->name = $type;
                break;
            }
        }

        if (array_key_exists($this->type, self::TYPE_ALIAS)) {
            $this->type = self::TYPE_ALIAS[$this->type];
        }

        if (array_key_exists($this->name, self::TYPE_NAME)) {
            $this->name = call_user_func(self::TYPE_NAME[$this->name], $value);
        }
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
