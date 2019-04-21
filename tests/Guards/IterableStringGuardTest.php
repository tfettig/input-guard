<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use Exception;
use InputGuard\Guards\IterableStringGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class IterableStringGuardTest extends TestCase
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
        $val = new IterableStringGuard($input);


        self::assertTrue($val->success(), $message);
        self::assertSame(array_map('\strval', $input), $val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function successProvider(): array
    {
        return [
            [[], 'An empty array'],
            [['two', 1, true, 234.23], 'Array of types that can be cast to strings.'],
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
        $val = new IterableStringGuard($input);

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
        $object = new class()
        {
            public function __toString()
            {
                return 'success';
            }
        };

        return [
            [[$object], 'A class with toString.'],
        ];
    }
}
