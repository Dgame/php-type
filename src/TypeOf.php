<?php

namespace Dgame\Type;

/**
 * Class TypeInfo
 * @package Dgame\Type
 */
class TypeOf extends TypeId
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
     * Typeof constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $type = null;
        $name = null;

        foreach (self::TYPE_CALLBACKS as $typeId => $callback) {
            if ($callback($value)) {
                $type = $typeId;
                $name = $typeId;
                break;
            }
        }

        if (array_key_exists($type, self::TYPE_ALIAS)) {
            $type = self::TYPE_ALIAS[$type];
        }

        if (array_key_exists($name, self::TYPE_NAME)) {
            $name = call_user_func(self::TYPE_NAME[$name], $value);
        }

        parent::__construct($type, $name);
    }
}
