<?php

declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\InstanceOfGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class InstanceOfGuardTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess(): void
    {
        $input = new stdClass();

        $val = new InstanceOfGuard($input, stdClass::class);
        self::assertTrue($val->success());
        self::assertSame($input, $val->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure(): void
    {
        $input = "bob's your uncle";

        $val = new InstanceOfGuard($input, stdClass::class);
        self::assertFalse($val->success());
        self::assertNull($val->value());
    }
}
