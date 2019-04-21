<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use Exception;
use InputGuard\Guards\IterableStringableGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class IterableStringableGuardTest extends TestCase
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
        $val = new IterableStringableGuard($input);

        self::assertTrue($val->success(), $message);

        $cast_if_not_object = static function ($element) {
            return \is_object($element) ? $element : (string)$element;
        };

        self::assertSame(array_map($cast_if_not_object, $input), $val->value(), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function successProvider(): array
    {
        $object = new class()
        {
            public function __toString()
            {
                return 'success';
            }
        };

        return [
            [[], 'An empty array'],
            [[$object, 'two', 1, true, 234.23], 'Array of types that can be cast to strings.'],
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
        $val = new IterableStringableGuard($input);

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
            [[new stdClass()], 'A class without toString.'],
            [[[1], [1, 3, 4]], 'An iterable of arrays'],
        ];
    }
}
