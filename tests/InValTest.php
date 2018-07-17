<?php
declare(strict_types=1);

namespace InValTest;

use InVal\Configuration;
use InVal\InVal;
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
        $this->inVal = new InVal(new Configuration());
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
    public function testIntValidateCreation(): void
    {
        self::assertInstanceOf(IntVal::class, $this->inVal->valInt(''));
    }
}
