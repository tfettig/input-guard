<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\FailAlwaysGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class FailAlwaysGuardTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValue(): void
    {
        self::assertNull((new FailAlwaysGuard())->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess(): void
    {
        self::assertFalse((new FailAlwaysGuard())->success());
    }
}
