<?php
declare(strict_types=1);

namespace InputGuardTests;

use ArrayObject;
use InputGuard\InputGuard;
use PHPUnit\Framework\TestCase;
use stdClass;

class InputGuardTest extends TestCase
{
    /**
     * @var InputGuard
     */
    private $validation;

    public function setUp(): void
    {
        $this->validation = new InputGuard();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAutoload(): void
    {
        self::assertInstanceOf(InputGuard::class, $this->validation);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testValue(): void
    {
        self::assertSame($this->validation, $this->validation->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClone(): void
    {
        self::assertEquals($this->validation, clone $this->validation);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccessSuccess(): void
    {
        self::assertTrue($this->validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccessFailure(): void
    {
        $this->validation->int('fail');
        self::assertFalse($this->validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRemovingDuplicatedErrorMessages(): void
    {
        $this->validation->int('error')
                         ->errorMessage('The same message');
        $this->validation->float('error')
                         ->errorMessage('The same message')
                         ->errorMessage('The same message');

        $this->validation->success();

        self::assertCount(1, $this->validation->pullErrorMessages());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBoolVal(): void
    {
        $input = false;
        self::assertSame($input, $this->validation->bool($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntVal(): void
    {
        $input = 1;
        self::assertSame($input, $this->validation->int($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatVal(): void
    {
        $input = 1.1;
        self::assertSame($input, $this->validation->float($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInstanceOfVal(): void
    {
        $input = new stdClass();
        self::assertSame($input, $this->validation->instanceOf($input, stdClass::class)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringVal(): void
    {
        $input = 'string';
        self::assertSame($input, $this->validation->string($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringableVal(): void
    {
        $input = new class()
        {
            public function __toString()
            {
                return 'string';
            }
        };

        self::assertSame($input, $this->validation->stringable($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testArrayVal(): void
    {
        $input = [0, 1, 2];
        self::assertSame($input, $this->validation->array($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableVal(): void
    {
        $input = new ArrayObject();
        self::assertSame($input, $this->validation->iterable($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableIntVal(): void
    {
        $input = [1, 2, 3];
        self::assertSame($input, $this->validation->iterableInt($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableFloatVal(): void
    {
        $input = [1.1, 2.9, 3];
        self::assertSame($input, $this->validation->iterableFloat($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableStringVal(): void
    {
        $input = ['one', 'two', 'three'];
        self::assertSame($input, $this->validation->iterableString($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableStringableVal(): void
    {
        $input = [
            new class()
            {
                public function __toString()
                {
                    return 'success';
                }
            }
        ];
        self::assertSame($input, $this->validation->iterableStringable($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInListVal(): void
    {
        $input = 0;
        self::assertSame($input, $this->validation->inList($input, [0])->value());
    }
}
