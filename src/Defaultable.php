<?php

declare(strict_types=1);

namespace Dgame\Type;

interface Defaultable
{
    public function getDefaultValue(): mixed;
}
