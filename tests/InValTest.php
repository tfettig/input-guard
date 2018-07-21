<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use PHPUnit\Framework\TestCase;
use stdClass;

class InValTest extends TestCase
{
    /**
     * @var InVal
     */
    private $inVal;

    public function setUp(): void
    {
        $this->inVal = new InVal();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAutoload(): void
    {
        self::assertInstanceOf(InVal::class, $this->inVal);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testValue(): void
    {
        self::assertSame($this->inVal, $this->inVal->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClone(): void
    {
        self::assertEquals($this->inVal, clone $this->inVal);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccessSuccess(): void
    {
        self::assertTrue($this->inVal->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccessFailure(): void
    {
        $this->inVal->intVal('fail');
        self::assertFalse($this->inVal->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntValBuilder(): void
    {
        $input = 1;
        self::assertSame($input, $this->inVal->intVal($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatValBuilder(): void
    {
        $input = 1.1;
        self::assertSame($input, $this->inVal->floatVal($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInstanceOfValBuilder(): void
    {
        $input = new stdClass();
        self::assertSame($input, $this->inVal->instanceOfVal($input, stdClass::class)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringValBuilder(): void
    {
        $input = 'string';
        self::assertSame($input, $this->inVal->stringVal($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringableValBuilder(): void
    {
        $input = new class()
        {
            public function __toString()
            {
                return 'string';
            }
        };

        self::assertSame($input, $this->inVal->stringableVal($input)->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRemovingDuplicatedErrorMessages(): void
    {
        $this->inVal->intVal('error')
                    ->errorMessage('The same message');
        $this->inVal->floatVal('error')
                    ->errorMessage('The same message')
                    ->errorMessage('The same message');

        $this->inVal->success();

        self::assertCount(1, $this->inVal->pullErrorMessages());
    }
}
