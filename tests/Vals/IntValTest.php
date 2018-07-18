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
    private static $generator;

    /**
     * @var Configuration
     */
    private static $configuration;

    public static function setUpBeforeClass()
    {
        self::$generator     = Factory::create();
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
        $val = new IntVal($input, self::$configuration);
        self::assertTrue($val->success());
        self::assertSame((int)$input, $val->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        $val = new IntVal('one', self::$configuration);
        self::assertFalse($val->success());
        self::assertSame($val->value(), self::$configuration->defaultValue(IntVal::class));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            [random_int(PHP_INT_MIN, PHP_INT_MAX)],
            [(string)random_int(PHP_INT_MIN, PHP_INT_MAX)],
        ];
    }
}