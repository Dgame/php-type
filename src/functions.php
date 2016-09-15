<?php

namespace Dgame\Type;

/**
 * @param $value
 *
 * @return Type
 */
function typeof($value): Type
{
    return new Type(new TypeInfo($value));
}