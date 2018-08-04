<?php
declare(strict_types=1);

namespace InValTest\Vals;

use ArrayObject;
use InVal\Vals\InListVal;
use Iterator;
use PHPUnit\Framework\TestCase;
use stdClass;

class InListValTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param            $input
     * @param iterable   $list
     * @param bool       $strict
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, iterable $list, bool $strict, string $message): void
    {
        $val = new InListVal($input, $list);

        $strict ? $val->strict() : $val->nonStrict();

        self::assertTrue($val->success(), $message);
        self::assertSame($input, $val->value(), $message);
    }

    public function successProvider(): array
    {
        $stdClass = new stdClass();
        return [
            [1, [1, 2, 3], true, 'Simple strict array'],
            ['1', [1, 2, 3], false, 'Simple non-strict array'],
            [1, new ArrayObject([1, 2, 3]), true, 'An ArrayObject'],
            [1, $this->iterator(), false, 'An Iterator'],
            ['1', $this->iterator(), true, 'An Iterator'],
            [$stdClass, ['something', $stdClass, 5.3], true, 'Looking the same object'],
            [new stdClass(), ['something', new stdClass(), 5.3], false, 'Looking for two instances of a class.'],
        ];
    }

    /**
     * @dataProvider failureProvider
     *
     * @param            $input
     * @param iterable   $list
     * @param bool       $strict
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, iterable $list, bool $strict, string $message): void
    {
        $val = new InListVal($input, $list);

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
        return new class() implements Iterator
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
