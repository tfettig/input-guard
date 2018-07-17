<?php
declare(strict_types=1);

namespace InValTest\Vals;

use Faker\Factory;
use InVal\Configuration;
use InVal\Vals\FloatVal;
use InVal\Vals\IntVal;
use PHPUnit\Framework\TestCase;

class FloatValTest extends TestCase
{
    /**
     * @var Configuration
     */
    private static $configuration;

    public static function setUpBeforeClass()
    {
        self::$configuration = new Configuration();
    }

    /**
     * @dataProvider successProvider
     *
     * @param $input
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input): void
    {
        $val = new FloatVal($input, self::$configuration);
        self::assertTrue($val->success());
        self::assertSame((float)$input, $val->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        $val = new FloatVal('one.point.one', self::$configuration);
        self::assertFalse($val->success());
        self::assertSame($val->value(), self::$configuration->defaultValue(IntVal::class));
    }

    public function successProvider(): array
    {
        static $generator;
        if (!$generator) {
            $generator = Factory::create();
        }

        return [
            [$generator->numberBetween(PHP_INT_MIN, PHP_INT_MAX)],
            [$generator->randomFloat()],
            [(string)$generator->numberBetween(PHP_INT_MIN, PHP_INT_MAX)],
            [(string)$generator->randomFloat()],
        ];
    }
}
