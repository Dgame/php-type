<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class UnionBuilder
 * @package Dgame\Type
 */
final class UnionBuilder
{
    private const IS_ALPHA_PATTERN = '/^[a-z]+/Si';

    /**
     * @param string $suffix
     * @param Type   $type
     *
     * @return Type
     */
    public static function build(string $suffix, Type $type): Type
    {
        $union = new UnionType($type);
        foreach (explode('|', $suffix) as $subType) {
            $subType = trim($subType);
            if (strpos($subType, '?') === 0) {
                $union->setIsNullable(true);
                $subType = substr($subType, 1);
            }

            if (preg_match(self::IS_ALPHA_PATTERN, $subType) === 1) {
                $union->appendType(Type::parse($subType));
            }
        }

        return $union->unwrap();
    }
}
