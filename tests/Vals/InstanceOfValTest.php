<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\InstanceOfVal;
use PHPUnit\Framework\TestCase;
use stdClass;

class InstanceOfValTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess(): void
    {
        $input = new stdClass();

        $val = new InstanceOfVal($input, stdClass::class);
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

        $val = new InstanceOfVal($input, stdClass::class);
        self::assertFalse($val->success());
        self::assertNull($val->value());
    }
}
