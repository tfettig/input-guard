<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use Exception;
use InputGuard\Guards\FloatGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class FloatGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param mixed  $input
     * @param float  $expected
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, float $expected, string $message): void
    {
        $val = new FloatGuard($input);
        self::assertSame($expected, $val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function successProvider(): array
    {
        return [
            [33545313590, 33545313590.0, 'Integer'],
            [1.5, 1.5, 'Float'],
            ['33545313590', 33545313590.0, 'Integer as a string'],
            ['24332423.23423', 24332423.23423, 'Float as a string'],
        ];
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed  $input
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, string $message): void
    {
        $val = new FloatGuard($input);

        self::assertNull($val->value(), $message);
    }

    public function failureProvider(): array
    {
        return [
            ['one.point.one', 'Input as string'],
            [true, 'Input as boolean'],
            [new stdClass(), 'Input as object'],
        ];
    }
}
