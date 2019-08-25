<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class TypeParser
 * @package Dgame\Type
 */
final class TypeParser
{
    private const  TYPE_CALLBACKS = [
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
     * @param mixed $value
     *
     * @return Type
     */
    public static function fromValue($value): Type
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
    private static function interpretValue($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param string $typeName
     *
     * @return Type
     */
    public static function parse(string $typeName): Type
    {
        $basicType = new BasicTypeParser($typeName);
        switch ($basicType->getBasicType()) {
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
                $type = new ObjectType($basicType->getTypeName());
                break;
            case 'void':
                $type = new VoidType();
                break;
            case 'array':
                $type = self::parseArray($typeName, $basicType);
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
                $type = new UserDefinedType($basicType->getBasicType());
                break;
        }

        $type->setIsNullable($basicType->isNullable());
        $suffix = $basicType->getSuffix();

        return ArrayBuilder::build($suffix, UnionBuilder::build($suffix, $type));
    }

    /**
     * @param string          $typeName
     * @param BasicTypeParser $basicType
     *
     * @return ArrayType
     */
    private static function parseArray(string $typeName, BasicTypeParser $basicType): ArrayType
    {
        $type = ArrayType::parseGeneric($typeName);
        if ($type !== null) {
            $basicType->setSuffixBehindArray();

            return $type;
        }

        return new ArrayType();
    }
}
