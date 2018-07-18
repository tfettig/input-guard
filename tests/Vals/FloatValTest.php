<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\FloatVal;
use PHPUnit\Framework\TestCase;

class FloatValTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param $input
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input): void
    {
        $val = new FloatVal($input);
        self::assertTrue($val->success());
        self::assertSame((float)$input, $val->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        $val = new FloatVal('one.point.one');
        self::assertFalse($val->success());
        self::assertNull($val->value());
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            [random_int(PHP_INT_MIN, PHP_INT_MAX)],
            [342343.233417],
            [(string)random_int(PHP_INT_MIN, PHP_INT_MAX)],
            ['342343.233417'],
        ];
    }
}
