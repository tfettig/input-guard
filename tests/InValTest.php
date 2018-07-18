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
}
