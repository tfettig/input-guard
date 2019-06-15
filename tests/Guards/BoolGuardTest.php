<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\BoolGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class BoolGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param mixed  $input
     * @param bool   $expected
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, bool $expected, string $message): void
    {
        $val = new BoolGuard($input);

        self::assertTrue($val->success(), "$message success");
        self::assertSame($expected, $val->value(), "$message value");
    }

    public function successProvider(): array
    {
        return [
            [true, true, 'Bool true'],
            [false, false, 'Bool false'],
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
        $val = new BoolGuard($input);

        self::assertFalse($val->success(), "Success $message");
        self::assertNull($val->value(), "Value $message");
    }

    public function failureProvider(): array
    {
        return [
            [1, 'Integer one'],
            [0, 'Integer zero'],
            ['1', 'String one'],
            ['0', 'String zero'],
            ['', 'Empty string'],
        ];
    }

    /**
     * @dataProvider successWithPseudoBools
     *
     * @param mixed  $input
     * @param bool   $expected
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccessWithPseudoBools($input, bool $expected, string $message): void
    {
        $val = (new BoolGuard($input))->pseudoBools();

        self::assertTrue($val->success(), "$message success");
        self::assertSame($expected, $val->value(), "$message value");
    }

    public function successWithPseudoBools(): array
    {
        return [
            [1, true, 'Integer one'],
            [0, false, 'Integer zero'],
            ['1', true, 'String one'],
            ['0', false, 'String zero'],
            ['', false, 'Empty string'],
        ];
    }


    /**
     * @dataProvider failureWithPseudoBools
     *
     * @param mixed  $input
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailureWithPseudoBools($input, string $message): void
    {
        $val = (new BoolGuard($input))->pseudoBools();

        self::assertFalse($val->success(), "$message success");
        self::assertNull($val->value(), "$message value");
    }

    public function failureWithPseudoBools(): array
    {
        return [
            ['failure', 'String'],
            [2, 'Integer two'],
            [[1], 'Array with a value'],
            [new stdClass(), 'Object'],
        ];
    }
}
