<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\IterableIntVal;
use PHPUnit\Framework\TestCase;

class IterableIntValTest extends TestCase
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
        $val = new IterableIntVal($input);

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
            [['1', '2'], 'Array of stringed integers'],
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
        $val = new IterableIntVal($input);
        $val->between(0, 10);
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
            [[-1, 2], 'Value too small'],
            [[1, 11], 'Value too large'],
            [[1, 2, 3], 'Too many elements'],
            [[1], 'Too few elements'],
        ];
    }
}
