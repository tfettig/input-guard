<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\FloatVal;
use PHPUnit\Framework\TestCase;
use stdClass;

class FloatValTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param            $input
     *
     * @param float|null $min
     * @param float|null $max
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, ?float $min, ?float $max, string $message): void
    {
        $val = new FloatVal($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->min($min);
        } elseif ($max !== null) {
            $val->max($max);
        }

        self::assertTrue($val->success(), $message);
        self::assertSame((float)$input, $val->value(), $message);
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed  $input
     * @param float  $min
     * @param float  $max
     * @param string $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, ?float $min, ?float $max, string $message): void
    {
        $val = new FloatVal($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->min($min);
        } elseif ($max !== null) {
            $val->max($max);
        }
        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    public function failureProvider(): array
    {
        return [
            ['one.point.one', null, null, 'Input as string'],
            [true, 0, 2.5, 'Input as boolean'],
            ['', 0, 2.5, 'Input as empty string'],
            [new stdClass(), 0, 2.5, 'Input as object'],
            [0, 1, 2, 'Input less then min'],
            [3, 1, 2, 'Input greater than max'],
        ];
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            [33545313590, null, null, "int between PHP's min and max"],
            ['33545313590', null, null, "'int' between PHP's min and max"],
            ['24332423.23423', null, null, "float between PHP's min and max"],
            [1.5, 0, 2.5, 'Using between'],
            [1.5, .9, null, 'Using min'],
            [1.5, null, 2.5, 'Using max'],
            [1, 1, 2, 'Input and min are equal'],
            [2, 1, 2, 'Input and max are equal'],
        ];
    }
}
