<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class BasicTypeParser
 * @package Dgame\Type
 */
final class BasicTypeParser
{
    private const NON_ALLOWED_CLASS_NAME_SYMBOL = '/[^a-zA-Z0-9_\\\]/';
    private const GENERIC_ARRAY_END             = '>';

    /**
     * @var string
     */
    private $typeName;
    /**
     * @var bool
     */
    private $nullable = false;
    /**
     * @var string
     */
    private $basicType;
    /**
     * @var string
     */
    private $suffix = '';

    public function __construct(string $typeName)
    {
        $this->typeName = $this->basicType = trim($typeName);

        $this->parseNullable();
        $this->parseBasicType();
    }

    private function parseNullable(): void
    {
        if (strpos($this->typeName, '?') === 0) {
            $this->nullable = true;
            $this->typeName = $this->basicType = substr($this->typeName, 1);
        }
    }

    private function parseBasicType(): void
    {
        if (preg_match(self::NON_ALLOWED_CLASS_NAME_SYMBOL, $this->typeName, $match, PREG_OFFSET_CAPTURE) === 1) {
            $index = $match[0][1];

            $this->basicType = substr($this->typeName, 0, $index);
            $this->suffix    = substr($this->typeName, $index);
        }
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return string
     */
    public function getBasicType(): string
    {
        return $this->basicType;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->isNullable() ? $this->suffix . '|null' : $this->suffix;
    }

    /**
     *
     */
    public function setSuffixBehindArray(): void
    {
        $index = strrpos($this->suffix, self::GENERIC_ARRAY_END);
        if ($index !== false) {
            $this->suffix = substr($this->suffix, $index + 1);
        }
    }
}
