<?php

namespace Dgame\Type;

/**
 * @param $value
 *
 * @return Type
 */
function typeof($value): Type
{
    return new Type(new TypeOf($value));
}

/**
 * @param string $type
 *
 * @return Type
 */
function typeid(string $type): Type
{
    return new Type(new TypeId($type));
}