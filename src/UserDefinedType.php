<?php

declare(strict_types=1);

namespace Dgame\Type;

/**
 * Class UserDefinedType
 * @package Dgame\Type
 */
final class UserDefinedType extends ObjectType
{
    /**
     * @param TypeVisitorInterface $visitor
     */
    public function accept(TypeVisitorInterface $visitor): void
    {
        $visitor->visitUserDefined($this);
    }
}
