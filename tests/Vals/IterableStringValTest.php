<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\IterableStringVal;
use PHPUnit\Framework\TestCase;

class IterableStringValTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param            $input
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, string $message): void
    {
        $val = new IterableStringVal($input);

        self::assertTrue($val->success(), $message);
        self::assertSame($input, $val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            [[1, 2], 'Array of integers'],
            [[false, true], 'Array of booleans'],
            [['one', 'two'], 'Array of strings'],
        ];
    }


    /**
     * @dataProvider failureProvider
     *
     * @param            $input
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, string $message): void
    {
        $val = new IterableStringVal($input);
        $val->betweenLen(1, 10);
        $val->betweenCount(2, 2);

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function failureProvider(): array
    {
        return [
            [['a', ''], 'Value too small'],
            [['a', 'bbb_bbb_bbb'], 'Value too large'],
            [['a', 'b', 'c'], 'Too many elements'],
            [['a'], 'Too few elements'],
        ];
    }
}
