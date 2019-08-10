<?php

namespace Dgame\Type;

/**
 * @param mixed $expression
 *
 * @return TypeOf
 */
function typeof($expression): TypeOf
{
    return TypeOfFactory::expression($expression);
}
