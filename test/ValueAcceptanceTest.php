<?php

declare(strict_types=1);

namespace Dgame\Test\Type;

use Dgame\Type\ObjectType;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class AcceptanceTest
 * @package Dgame\Test\Type
 */
final class ValueAcceptanceTest extends TestCase
{
    public function testObjectAcceptValue(): void
    {
        $type = new ObjectType();
        $this->assertTrue($type->acceptValue(new stdClass(), false));
        $this->assertTrue($type->acceptValue(new stdClass(), true));
    }
}
