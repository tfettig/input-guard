<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\ArrayVal;
use PHPUnit\Framework\TestCase;

class ArrayValTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param mixed    $input
     * @param int|null $min
     * @param int|null $max
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, ?int $min, ?int $max, string $message): void
    {
        $val = new ArrayVal($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->minSize($min);
        } elseif ($max !== null) {
            $val->maxSize($max);
        }

        self::assertTrue($val->success(), $message);
        self::assertSame((array)$input, $val->value(), $message);
    }

    public function successProvider(): array
    {
        return [
            [[], null, null, 'Empty array',],
            [[0, 1, 2, 3, 4, 5], 3, 6, 'Array with values using between.',],
            [[0, 1, 2, 3, 4, 5], null, 6, 'Array with values using max.',],
            [[0, 1, 2, 3, 4, 5], 3, null, 'Array with values using min.',],
        ];
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed    $input
     * @param int|null $min
     * @param int|null $max
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, ?int $min, ?int $max, string $message): void
    {
        $val = new ArrayVal($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->minSize($min);
        } elseif ($max !== null) {
            $val->maxSize($max);
        }

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    public function failureProvider(): array
    {
        return [
            [null, null, null, 'Null input',],
            [[0, 1, 2, 3, 4, 5], 7, 10, 'Input less then min using between.',],
            [[0, 1, 2, 3, 4, 5], null, 1, 'Input more then max.',],
            [[0, 1, 2, 3, 4, 5], 10, null, 'Input less then min.',],
        ];
    }
}
