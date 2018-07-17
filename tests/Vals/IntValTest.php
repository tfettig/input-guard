<?php
declare(strict_types=1);

namespace InValTest\Vals;

use Faker\Factory;
use Faker\Generator;
use InVal\Configuration;
use InVal\Vals\IntVal;
use PHPUnit\Framework\TestCase;

class IntValTest extends TestCase
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var Configuration
     */
    private $confguration;

    public function setUp()
    {
        $this->generator    = Factory::create();
        $this->confguration = new Configuration();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess(): void
    {
        $input = $this->generator->numberBetween(PHP_INT_MIN, PHP_INT_MAX);
        $val   = new IntVal($input, $this->confguration);
        self::assertTrue($val->success());
        self::assertSame($input, $val->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        $val = new IntVal('one', $this->confguration);
        self::assertFalse($val->success());
        self::assertSame($val->value(), $this->confguration->defaultValue(IntVal::class));
    }
}
