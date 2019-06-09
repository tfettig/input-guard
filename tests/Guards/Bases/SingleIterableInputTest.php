<?php
declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use InputGuard\Guards\Bases\SingleIterableInput;
use Iterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class SingleIterableInputTest extends TestCase
{
    /**
     * @var callable
     */
    public static $singleIterableInputFactory;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$singleIterableInputFactory = function ($input): object {
            return new class ($input)
            {
                use SingleIterableInput;

                public function __construct($input, ?iterable $default = null)
                {
                    $this->input = $input;
                    $this->value = $default;
                }

                protected function validateIterableElement($element, &$value): bool
                {
                    $value = $element;
                    return true;
                }
            };
        };
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNullsAreAllowed(): void
    {
        $input = [1, null, 2];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->allowNullElement();

        self::assertTrue($guard->success());
        self::assertSame($input, $guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNullsAreNotAllowed(): void
    {
        $input = [1, null, 2];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);

        self::assertFalse($guard->success());
        self::assertNull($guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testEmptyArraySuccess(): void
    {
        $input = [];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);

        self::assertTrue($guard->success());
        self::assertSame($input, $guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIteratorSuccess(): void
    {
        $input = $this->iterator();

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);

        self::assertTrue($guard->success());
        self::assertSame($input, $guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBetweenCountSuccess(): void
    {
        $input = [0, 1, 2, 3, 4, 5];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->betweenCount(3, 6);

        self::assertTrue($guard->success());
        self::assertSame($input, $guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMaxCountSuccess(): void
    {
        $input = [0, 1, 2, 3, 4, 5];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->maxCount(6);

        self::assertTrue($guard->success());
        self::assertSame($input, $guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMinCountSuccess(): void
    {
        $input = [0, 1, 2, 3, 4, 5];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->minCount(3);

        self::assertTrue($guard->success());
        self::assertSame($input, $guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInputIsNullFailure(): void
    {
        $input = null;

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);

        self::assertFalse($guard->success());
        self::assertNull($guard->value());
    }

    /**
     * @dataProvider betweenCountFailureProvider
     *
     * @param iterable $input
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBetweenCountFailure(iterable $input, string $message): void
    {
        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->betweenCount(2, 2);

        self::assertFalse($guard->success(), $message);
        self::assertNull($guard->value(), $message);
    }

    public function betweenCountFailureProvider(): array
    {
        return [
            [[0, 1, 2], 'Too many'],
            [[0], 'Too few'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMaxCountFailure(): void
    {
        $input = [0, 1, 2, 3, 4, 5];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->maxCount(1);

        self::assertFalse($guard->success());
        self::assertNull($guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMinCountFailure(): void
    {
        $input = [0, 1];

        /** @var SingleIterableInput $guard */
        $guard = (self::$singleIterableInputFactory)($input);
        $guard->minCount(3);

        self::assertFalse($guard->success());
        self::assertNull($guard->value());
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
