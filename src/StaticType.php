<?php

declare(strict_types=1);

namespace Dgame\Type;

final class StaticType extends ObjectType
{
    public function __construct(string $name = 'static')
    {
        parent::__construct($name);
    }
}
