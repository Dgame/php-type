<?php

namespace Dgame\Type;

use Exception;
use ReflectionParameter;
use RuntimeException;

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
     * @throws Exception
     */
    public static function reflection(ReflectionParameter $parameter): Type
    {
        if (!$parameter->hasType()) {
            throw new RuntimeException('Parameter has no type');
        }

        $type = $parameter->getType();
        if ($type === null) {
            throw new RuntimeException('Parameter has no type');
        }

        if (!$type->isBuiltin()) {
            return new Type(Type::IS_OBJECT);
        }

        $alias = Type::alias((string) $type);
        if ($alias === Type::NONE) {
            throw new RuntimeException('No type found');
        }

        return new Type($alias);
    }

    /**
     * @param mixed $expression
     *
     * @return Type
     * @throws Exception
     */
    public static function expression($expression): Type
    {
        foreach (Type::TYPE_CALLBACK as $type => $callback) {
            if (is_callable($callback) && $callback($expression)) {
                return new Type($type);
            }
        }

        throw new RuntimeException('Unknown expression: ' . $expression);
    }
}
