<?php
declare(strict_types=1);

namespace InValTest;

use InVal\Configuration;
use InVal\Vals\IterableVal;
use InVal\Vals\BoolVal;
use InVal\Vals\FloatVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
use InVal\Vals\StringVal;
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
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDefaultValue(string $class): void
    {
        self::assertNull(self::$configuration->defaultValue($class));
    }

    public function defaultValueProvider(): array
    {
        return [
            [''],
            ['nonexistent'],
            [IterableVal::class],
            [BoolVal::class],
            [FloatVal::class],
            [InstanceOfVal::class],
            [IntVal::class],
            [StringVal::class],
        ];
    }
}
