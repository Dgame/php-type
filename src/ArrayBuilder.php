<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class ArrayBuilder
 * @package Dgame\Type
 */
final class ArrayBuilder
{
    private const ARRAY_INDEX_PATTERN = '/\s*\[\s*(?<index>\w*)\s*\]\s*/S';

    /**
     * @param string $suffix
     * @param Type   $type
     *
     * @return Type
     */
    public static function build(string $suffix, Type $type): Type
    {
        $offset = 0;
        while (preg_match(self::ARRAY_INDEX_PATTERN, $suffix, $matches, 0, $offset) === 1) {
            $offset    += strlen($matches[0]);
            $indexType = !empty($matches['index']) ? Type::parse($matches['index']) : null;
            $type      = new ArrayType($type, 1, $indexType);
        }

        return $type;
    }
}
