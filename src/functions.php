<?php

namespace Dgame\Type;

/**
 * @param $expression
 *
 * @return Type
 */
function typeof($expression): Type
{
    return Type::of($expression);
}