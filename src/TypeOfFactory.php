<?php

declare(strict_types=1);

namespace Dgame\Type;

use ReflectionParameter;
use RuntimeException;

/**
 * Class TypeOfFactory
 * @package Dgame\Type
 */
final class TypeOfFactory
{
    /**
     * @param ReflectionParameter $parameter
     *
     * @return TypeOf
     */
    public static function reflection(ReflectionParameter $parameter): TypeOf
    {
        if (!$parameter->hasType()) {
            throw new RuntimeException('Parameter has no type');
        }

        $type = $parameter->getType();
        if ($type === null) {
            throw new RuntimeException('Parameter has no type');
        }

        $alias = TypeOf::alias((string) $type);
        if ($alias === TypeOf::NONE) {
            throw new RuntimeException('No type found');
        }

        return new TypeOf($alias);
    }

    /**
     * @param mixed $expression
     *
     * @return TypeOf
     */
    public static function expression($expression): TypeOf
    {
        foreach (TypeOf::TYPE_CALLBACK as $type => $callback) {
            if (is_callable($callback) && $callback($expression)) {
                return new TypeOf($type);
            }
        }

        throw new RuntimeException('Unknown expression: ' . $expression);
    }
}
