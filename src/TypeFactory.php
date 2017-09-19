<?php

namespace Dgame\Type;

use Exception;
use ReflectionParameter;
use function Dgame\Ensurance\enforce;

/**
 * Class TypeFactory
 * @package Dgame\Type
 */
final class TypeFactory
{
    /**
     * @param ReflectionParameter $parameter
     *
     * @return Type
     */
    public static function reflection(ReflectionParameter $parameter): Type
    {
        enforce($parameter->hasType())->orThrow('Parameter has no type');

        if (!$parameter->getType()->isBuiltin()) {
            return new Type(Type::IS_OBJECT);
        }

        $type = array_search((string) $parameter->getType(), Type::EXPORT);
        enforce($type !== false)->orThrow('No type found');

        return new Type($type);
    }

    /**
     * @param $expression
     *
     * @return Type
     * @throws Exception
     */
    public static function expression($expression): Type
    {
        foreach (Type::TYPE_CALLBACK as $type => $callback) {
            if ($callback($expression)) {
                return new Type($type);
            }
        }

        throw new Exception('Unknown expression: ' . $expression);
    }
}