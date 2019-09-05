<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class ArrayType
 * @package Dgame\Type
 */
final class ArrayType extends Type
{
    /**
     * @var Type|null
     */
    private $valueType;
    /**
     * @var Type|null
     */
    private $indexType;

    /**
     * ArrayType constructor.
     *
     * @param Type|null $valueType
     * @param Type|null $indexType
     */
    public function __construct(Type $valueType = null, Type $indexType = null)
    {
        $this->valueType = $valueType;
        $this->indexType = $indexType;
    }

    /**
     * @return bool
     */
    public function hasIndexType(): bool
    {
        return $this->indexType !== null;
    }

    /**
     * @return bool
     */
    public function hasValueType(): bool
    {
        return $this->valueType !== null;
    }

    /**
     * @return array
     */
    public function getDefaultValue(): array
    {
        return [];
    }

    /**
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitArray($this);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     *
     * @return bool
     */
    public function acceptValue($value, bool $strict): bool
    {
        if ($this->valueType === null) {
            return is_array($value);
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $key => $val) {
            if (!$this->valueType->acceptValue($val, $strict)) {
                return false;
            }

            if ($this->indexType !== null && !$this->indexType->acceptValue($key, $strict)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if ($this->valueType !== null) {
            if ($this->indexType !== null) {
                return sprintf('array<%s, %s>', $this->indexType->getDescription(), $this->valueType->getDescription());
            }

            return sprintf('%s[]', $this->valueType->getDescription());
        }

        return 'array';
    }

    /**
     * @return Type
     */
    public function getBasicType(): Type
    {
        $type = $this->valueType;
        while ($type !== null) {
            $resolver = new TypeResolver($type);
            $array    = $resolver->getArrayType();
            if ($array === null) {
                break;
            }
            $type = $array->getValueType();
        }

        return $type ?? new MixedType();
    }

    /**
     * @return Type
     */
    public function getIndexType(): Type
    {
        return $this->indexType ?? new IntType();
    }

    /**
     * @return Type|null
     */
    public function getValueType(): ?Type
    {
        return $this->valueType;
    }
}
