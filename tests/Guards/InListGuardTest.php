<?php

declare(strict_types=1);

namespace InputGuardTests\Guards;

use ArrayObject;
use InputGuard\Guards\InListGuard;
use Iterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class InListGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param mixed    $input
     * @param iterable $list
     * @param bool     $strict
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, iterable $list, bool $strict, string $message): void
    {
        $val = new InListGuard($input, $list, null, $strict);

        $strict ? $val->strict() : $val->nonStrict();

        self::assertTrue($val->success(), $message);
        self::assertSame($input, $val->value(), $message);
    }

    public function successProvider(): array
    {
        return array_merge($this->nonStrictSuccessProvider(), $this->strictSuccessProvider());
    }

    public function strictSuccessProvider(): array
    {
        $stdClass = new stdClass();
        return [
            [1, [1, 2, 3], true, 'Simple strict array'],
            [1, new ArrayObject([1, 2, 3]), true, 'An ArrayObject'],
            ['1', $this->iterator(), true, 'An Iterator'],
            [$stdClass, ['something', $stdClass, 5.3], true, 'Looking the same object'],
        ];
    }

    public function nonStrictSuccessProvider(): array
    {
        return [
            ['1', [1, 2, 3], false, 'Simple non-strict array'],
            [1, $this->iterator(), false, 'An Iterator'],
            [new stdClass(), ['something', new stdClass(), 5.3], false, 'Looking for two instances of a class.'],
        ];
    }

    /**
     * @dataProvider nonStrictSuccessProvider
     *
     * @param mixed    $input
     * @param iterable $list
     * @param bool     $strict
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testChangingFromNonStrictToStrict($input, iterable $list, bool $strict, string $message): void
    {
        $val = new InListGuard($input, $list, null, $strict);
        $val->strict();

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    /**
     * @dataProvider failureProvider
     *
     * @param            $input
     * @param iterable   $list
     * @param bool       $strict
     * @param string     $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, iterable $list, bool $strict, string $message): void
    {
        $val = new InListGuard($input, $list);

        $strict ? $val->strict() : $val->nonStrict();

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }


    public function failureProvider(): array
    {
        return [
            ['1', [1, 2, 3], true, 'Simple strict array'],
            [5, [1, 2, 3], true, 'Simple array not in the list.'],
            [5, new ArrayObject([1, 2, 3]), true, 'An ArrayObject'],
            ['nope rope', $this->iterator(), true, 'An Iterator'],
            ['nope rope', $this->iterator(), false, 'An Iterator'],
            [new stdClass(), [new stdClass(), 'something', 5.3], true, 'Looking for a class.'],
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
