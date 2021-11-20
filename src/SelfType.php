<?php

declare(strict_types=1);

namespace Dgame\Type;

final class SelfType extends ObjectType
{
    public function __construct(string $name = 'self')
    {
        parent::__construct($name);
    }
}
