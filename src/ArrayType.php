<?php

declare(strict_types=1);

namespace Dgame\Type;

final class ArrayType extends Type implements Defaultable, Castable
{
    public function __construct(private ?Type $valueType = null, private ?Type $keyType = null)
    {
    }

    public function getValueType(): ?Type
    {
        return $this->valueType;
    }

    public function getKeyType(): ?Type
    {
        return $this->keyType;
    }

    public function isAssignable(Type $other): bool
    {
        if (!($other instanceof $this)) {
            return false;
        }

        return $this->isAssignableValue($other) && $this->isAssignableKey($other);
    }

    private function isAssignableValue(self $other): bool
    {
        if ($this->valueType === null || $other->valueType === null) {
            return true; // We cannot identify the type so we have to assume it is mixed to prevent false-positives
        }

        return $other->valueType instanceof $this->valueType || $other->valueType instanceof MixedType;
    }

    private function isAssignableKey(self $other): bool
    {
        if ($this->keyType === null || $other->keyType === null) {
            return true; // We cannot identify the type so we have to assume it is mixed to prevent false-positives
        }

        return $other->keyType instanceof $this->keyType || $other->keyType instanceof MixedType;
    }

    /**
     * @return array{}
     */
    public function getDefaultValue(): array
    {
        return [];
    }

    /**
     * @param mixed $value
     *
     * @return array<int|string, mixed>
     */
    public function cast(mixed $value): array
    {
        return (array) $value;
    }

    public function isBuiltIn(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        if ($this->valueType !== null && $this->keyType !== null) {
            return 'array<' . $this->keyType->getName() . ', ' . $this->valueType->getName() . '>';
        }

        if ($this->valueType !== null) {
            return  'array<mixed, ' . $this->valueType->getName() . '>';
        }

        if ($this->keyType !== null) {
            return  'array<' . $this->keyType->getName() . ', mixed>';
        }

        return 'array';
    }
}
