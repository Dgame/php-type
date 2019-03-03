<?php

namespace Dgame\Type;

use Exception;

/**
 * @param mixed $expression
 *
 * @return Type
 * @throws Exception
 */
function typeof($expression): Type
{
    return TypeFactory::expression($expression);
}
