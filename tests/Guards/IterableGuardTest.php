<?php

declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\IterableGuard;
use Iterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class IterableGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param mixed  $input
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, string $message): void
    {
        $val = new IterableGuard($input);

        self::assertTrue($val->success(), $message);
        self::assertSame($input, $val->value(), $message);
    }

    public function successProvider(): array
    {
        return [
            [[0, 1, 2, 3, 4, 5], 'Array',],
            [$this->iterator(), 'Iterator',],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure(): void
    {
        $val = new IterableGuard(new stdClass());

        self::assertFalse($val->success());
        self::assertNull($val->value());
    }

    /**
     * @dataProvider valueAsArrayProvider
     *
     * @param mixed  $input
     * @param array  $expected
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValueAsArray($input, array $expected, string $message): void
    {
        $val = new IterableGuard($input);

        self::assertSame($expected, $val->valueAsArray(), $message);
    }

    public function valueAsArrayProvider(): array
    {
        return [
            [null, [], 'Invalid value'],
            [[0], [0], 'Valid array'],
            [$this->iterator(), ['1', '2', '3'], 'Valid iterator'],
        ];
    }

    /**
     * @return Iterator
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function iterator(): Iterator
    {
        return new class () implements Iterator
        {
            private $position = 0;

            private $array;

            public function __construct()
            {
                $this->array = ['1', '2', '3'];
            }

            public function rewind(): void
            {
                $this->position = 0;
            }

            public function current()
            {
                return $this->array[$this->position];
            }

            public function key()
            {
                return $this->position;
            }

            public function next(): void
            {
                ++$this->position;
            }

            public function valid(): bool
            {
                return isset($this->array[$this->position]);
            }
        };
    }
}
