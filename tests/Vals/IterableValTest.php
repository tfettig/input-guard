<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\IterableVal;
use Iterator;
use PHPUnit\Framework\TestCase;

class IterableValTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param mixed    $input
     * @param int|null $min
     * @param int|null $max
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, ?int $min, ?int $max, string $message): void
    {
        $val = new IterableVal($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->minSize($min);
        } elseif ($max !== null) {
            $val->maxSize($max);
        }

        self::assertTrue($val->success(), $message);
        self::assertSame($input, $val->value(), $message);
    }

    public function successProvider(): array
    {
        return [
            [[], null, null, 'Empty array',],
            [[0, 1, 2, 3, 4, 5], 3, 6, 'Array with values using between.',],
            [[0, 1, 2, 3, 4, 5], null, 6, 'Array with values using max.',],
            [[0, 1, 2, 3, 4, 5], 3, null, 'Array with values using min.',],
            [$this->iterator(), 3, 6, 'Iterator with values using between.',],
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
        $val = new IterableVal($input);

        if ($min !== null && $max !== null) {
            $val->between($min, $max);
        } elseif ($min !== null) {
            $val->minSize($min);
        } elseif ($max !== null) {
            $val->maxSize($max);
        }

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    public function failureProvider(): array
    {
        return [
            [null, null, null, 'Null input',],
            [[0, 1, 2, 3, 4, 5], 7, 10, 'Input less then min using between.',],
            [[0, 1, 2, 3, 4, 5], null, 1, 'Input more then max.',],
            [[0, 1, 2, 3, 4, 5], 10, null, 'Input less then min.',],
        ];
    }

    /**
     * @dataProvider valueasArrayProvider
     *
     * @param mixed  $input
     * @param array  $expected
     * @param string $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testValueAsArray($input, array $expected, string $message): void
    {
        $val = new IterableVal($input);

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
