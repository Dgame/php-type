<?php

declare(strict_types=1);

namespace Dgame\Type;

final class IterableType extends Type
{
    public function isAssignable(Type $other): bool
    {
        if ($other instanceof $this) {
            return true;
        }

        if ($other instanceof ObjectType) {
            return method_exists($other->getFullQualifiedName(), '__invoke');
        }

        return false;
    }

    public function isBuiltIn(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return 'iterable';
    }
}
