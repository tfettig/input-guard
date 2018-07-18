<?php
declare(strict_types=1);

namespace InValTest;

use InVal\Configuration;
use InVal\Vals\FloatVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
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
     * @dataProvider defaultValueProvider
     *
     * @param string $class
     * @param mixed  $default
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDefaultValue(string $class, $default): void
    {
        self::assertSame($default, self::$configuration->defaultValue($class));
    }

    public function defaultValueProvider(): array
    {
        return [
            ['', null],
            [IntVal::class, null],
            [FloatVal::class, null],
            [InstanceOfVal::class, null],
        ];
    }
}
