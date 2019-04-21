<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use Exception;
use InputGuard\Guards\IterableFloatGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class IterableFloatGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param            $input
     * @param string     $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, string $message): void
    {
        $val = new IterableFloatGuard($input);

        self::assertTrue($val->success(), $message);
        self::assertSame(array_map('\floatval', $input), $val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function successProvider(): array
    {
        return [
            [[1.3, 2], 'Array of floats'],
            [['1', '2.3'], 'Array of stringed integers'],
        ];
    }

    /**
     * @dataProvider failureProvider
     *
     * @param            $input
     * @param string     $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, string $message): void
    {
        $val = new IterableFloatGuard($input);
        $val->between(0, 10);
        $val->betweenCount(2, 2);

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
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
