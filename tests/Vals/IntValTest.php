<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\IntVal;
use PHPUnit\Framework\TestCase;

class IntValTest extends TestCase
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
        $val = new IntVal($input);
        self::assertTrue($val->success());
        self::assertSame((int)$input, $val->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        $val = new IntVal('one');
        self::assertFalse($val->success());
        self::assertNull($val->value());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            [random_int(PHP_INT_MIN, PHP_INT_MAX)],
            [(string)random_int(PHP_INT_MIN, PHP_INT_MAX)],
        ];
    }
}
