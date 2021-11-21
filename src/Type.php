<?php

declare(strict_types=1);

namespace Dgame\Type;

use InvalidArgumentException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;
use Stringable;
use UnexpectedValueException;

abstract class Type implements Stringable
{
    public static function fromReflectionParameter(ReflectionParameter $parameter): self
    {
        $type = self::fromReflectionType($parameter->getType());

        return self::resolve($type, $parameter);
    }

    public static function fromReflectionProperty(ReflectionProperty $property): self
    {
        $type = self::fromReflectionType($property->getType());

        return self::resolve($type, $property);
    }

    public static function fromReflectionType(?ReflectionType $type): self
    {
        if ($type === null) {
            return new MixedType();
        }

        if ($type instanceof ReflectionNamedType) {
            return self::identify($type->getName(), $type->allowsNull());
        }

        if ($type->allowsNull()) {
            return new UnionType(new MixedType(), new NullType());
        }

        return new MixedType();
    }

    public static function fromValue(mixed $value): self
    {
        if (is_array($value) && $value !== [] && self::isHomogenous($value)) {
            return new ArrayType(
                valueType: self::fromValue(current($value)),
                keyType:   self::fromValue(key($value))
            );
        }

        return self::fromName(is_object($value) ? get_class($value) : gettype($value));
    }

    public static function fromName(string $name, bool $allowsNull = false): self
    {
        $name = trim($name);
        if (str_starts_with($name, '?')) {
            $allowsNull = true;
            $name       = \Safe\substr($name, 1);
        }

        if (str_contains($name, '|')) {
            $names = array_map(static fn(string $name) => trim($name), explode('|', $name));

            return new UnionType(...array_map(static fn(string $name) => self::identify($name), $names));
        }

        if (str_contains($name, '&')) {
            $names = array_map(static fn(string $name) => trim($name), explode('&', $name));

            return new IntersectionType(...array_map(static fn(string $name) => self::identify($name), $names));
        }

        return self::identify($name, $allowsNull);
    }

    public function asNonNullable(): self
    {
        if (!$this->allowsNull()) {
            return $this;
        }

        if ($this instanceof UnionType) {
            $types = array_filter($this->getTypes(), static fn(Type $type) => !($type instanceof NullType));
            if (count($types) === 0) {
                throw new UnexpectedValueException('UnionType is empty');
            }

            return count($types) === 1 ? reset($types) : new UnionType(...$types);
        }

        throw new UnexpectedValueException('Only UnionTypes can contain null');
    }

    public function asNullable(): self
    {
        if ($this->allowsNull()) {
            return $this;
        }

        if ($this instanceof UnionType) {
            return new UnionType(new NullType(), ...$this->getTypes());
        }

        return new UnionType(new NullType(), $this);
    }

    public function getName(): string
    {
        return (string) $this;
    }

    final public function is(self $type): bool
    {
        return $type instanceof $this;
    }

    public function isAssignable(self $other): bool
    {
        return $this->is($other);
    }

    final public function accept(mixed $value): bool
    {
        return $this->isAssignable(self::fromValue($value));
    }

    public function allowsNull(): bool
    {
        return false;
    }

    abstract public function isBuiltIn(): bool;

    /**
     * @param array<mixed, mixed> $values
     *
     * @return bool
     */
    private static function isHomogenous(array $values): bool
    {
        if (count($values) <= 1) {
            return true;
        }

        $firstValue = current($values);
        $firstKey   = key($values);

        foreach ($values as $key => $value) {
            if (gettype($value) !== gettype($firstValue)) {
                return false;
            }

            if (gettype($key) !== gettype($firstKey)) {
                return false;
            }
        }

        return true;
    }

    private static function identify(string $name, bool $allowsNull = false): self
    {
        $arrayLevel = substr_count($name, '[]');
        $name       = rtrim($name, '[]');
        $type       = match (strtolower($name)) {
            'callable' => $allowsNull ? new UnionType(new CallableType(), new NullType()) : new CallableType(),
            'false' => $allowsNull ? new UnionType(new FalseType(), new NullType()) : new FalseType(),
            'iterable' => $allowsNull ? new UnionType(new IterableType(), new NullType()) : new IterableType(),
            'null' => $allowsNull ? throw new InvalidArgumentException('null is already null') : new NullType(),
            'mixed' => $allowsNull ? throw new InvalidArgumentException('mixed is nullable') : new MixedType(),
            'resource', 'resource (closed)' => $allowsNull ? new UnionType(new RessourceType(), new NullType()) : new RessourceType(),
            'array' => $allowsNull ? new UnionType(new ArrayType(), new NullType()) : new ArrayType(),
            'bool', 'boolean' => $allowsNull ? new UnionType(new BoolType(), new NullType()) : new BoolType(),
            'double', 'float', 'real' => $allowsNull ? new UnionType(new FloatType(), new NullType()) : new FloatType(),
            'int', 'integer' => $allowsNull ? new UnionType(new IntType(), new NullType()) : new IntType(),
            'string' => $allowsNull ? new UnionType(new StringType(), new NullType()) : new StringType(),
            'object' => $allowsNull ? new UnionType(new ObjectType($name), new NullType()) : new ObjectType($name),
            'static' => $allowsNull ? new UnionType(new StaticType(), new NullType()) : new StaticType(),
            'parent' => $allowsNull ? new UnionType(new ParentType(), new NullType()) : new ParentType(),
            'self' => $allowsNull ? new UnionType(new SelfType(), new NullType()) : new SelfType(),
            'never' => new NeverType(),
            default => self::identifyUnknown($name, $allowsNull)
        };

        for ($i = 0; $i < $arrayLevel; $i++) {
            $type = new ArrayType(valueType: $type, keyType: new IntType());
        }

        return $type;
    }

    private static function identifyUnknown(string $name, bool $allowsNull): self
    {
        if (str_starts_with($name, 'array')) {
            return self::parseArray($name, $allowsNull);
        }

        if (class_exists(class: $name, autoload: true)) {
            return $allowsNull ? new UnionType(new ObjectType($name), new NullType()) : new ObjectType($name);
        }

        return $allowsNull ? new UnionType(new UnknownType($name), new NullType()) : new UnknownType($name);
    }

    private static function parseArray(string $name, bool $allowsNull): self
    {
        if (\Safe\preg_match('/^array<(.+?),(.+?)>$/S', $name, $matches) === 1) {
            $type = new ArrayType(valueType: self::fromName($matches[2]), keyType: self::fromName($matches[1]));
        } elseif (\Safe\preg_match('/^array<(.+?)>$/S', $name, $matches) === 1) {
            $type = new ArrayType(valueType: self::fromName($matches[1]), keyType: new IntType());
        } else {
            $type = new ArrayType();
        }

        return $allowsNull ? new UnionType($type, new NullType()) : $type;
    }

    private static function resolve(self $type, ReflectionParameter|ReflectionProperty $reflection): self
    {
        if ($type instanceof SelfType || $type instanceof StaticType) {
            $declaringClass = $reflection->getDeclaringClass();
            if ($declaringClass !== null) {
                $class = $type::class;

                return new $class($declaringClass->getName());
            }
        }

        if ($type instanceof ParentType) {
            $declaringClass = $reflection->getDeclaringClass();
            if ($declaringClass !== null) {
                $parent = $declaringClass->getParentClass();
                if ($parent !== false) {
                    return new ParentType($parent->getName());
                }
            }
        }

        if ($type instanceof UnionType) {
            return self::resolveUnionType($type, $reflection);
        }

        return $type;
    }

    private static function resolveUnionType(UnionType $type, ReflectionParameter|ReflectionProperty $reflection): UnionType
    {
        $subTypes = [];
        foreach ($type->getTypes() as $subType) {
            $subTypes[] = self::resolve($subType, $reflection);
        }

        return new UnionType(...$subTypes);
    }
}
