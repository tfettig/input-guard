<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use InVal\Vals\FloatVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
use PHPUnit\Framework\TestCase;

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
    public function testIntValCreation(): void
    {
        self::assertInstanceOf(IntVal::class, $this->inVal->intVal(''));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatValCreation(): void
    {
        self::assertInstanceOf(FloatVal::class, $this->inVal->floatVal(''));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInstanceOfValCreation(): void
    {
        self::assertInstanceOf(InstanceOfVal::class, $this->inVal->instanceOfVal('', ''));
    }
}
