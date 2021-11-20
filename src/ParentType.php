<?php

declare(strict_types=1);

namespace Dgame\Type;

final class ParentType extends ObjectType
{
    public function __construct(string $name = 'parent')
    {
        parent::__construct($name);
    }
}
