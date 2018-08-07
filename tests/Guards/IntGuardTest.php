<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\IntGuard;
use PHPUnit\Framework\TestCase;
use stdClass;

class IntGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param            $input
     *
     * @param int|null   $min
     * @param int|null   $max
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, ?int $min, ?int $max, string $message): void
    {
        $val = new IntGuard($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->min($min);
        } elseif ($max !== null) {
            $val->max($max);
        }

        self::assertTrue($val->success(), $message);
        self::assertSame((int)$input, $val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            [random_int(PHP_INT_MIN, PHP_INT_MAX), null, null, "int between PHP's min and max"],
            [(string)random_int(PHP_INT_MIN, PHP_INT_MAX), null, null, "'int' between PHP's min and max"],
            [1, 0, 2, 'Using between'],
            [1, 1, null, 'Using min'],
            [1, null, 2, 'Using max'],
            [1, 1, 2, 'Input and min are equal'],
            [2, 1, 2, 'Input and max are equal'],
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
        $val = new IntGuard($input);

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
            [1.5, 0, 2, 'Input as float.'],
            [true, 0, 2, 'Input as boolean'],
            ['', 0, 2, 'Input as empty string'],
            [new stdClass(), 0, 2, 'Input as object'],
            [0, 1, 2, 'Input less then min'],
            [3, 1, 2, 'Input greater than max'],
        ];
    }
}
