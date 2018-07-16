<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use PHPUnit\Framework\TestCase;

class InValTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAutoload(): void
    {
        self::assertInstanceOf(InVal::class, new InVal());
    }
}
