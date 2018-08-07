<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\InstanceOfGuard;
use PHPUnit\Framework\TestCase;
use stdClass;

class InstanceOfGuardTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess(): void
    {
        $input = new stdClass();

        $val = new InstanceOfGuard($input, stdClass::class);
        self::assertTrue($val->success());
        self::assertSame($input, $val->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        $input = "bob's your uncle";

        $val = new InstanceOfGuard($input, stdClass::class);
        self::assertFalse($val->success());
        self::assertNull($val->value());
    }
}
