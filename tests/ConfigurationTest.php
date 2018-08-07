<?php
declare(strict_types=1);

namespace InputGuardTests;

use InputGuard\DefaultConfiguration;
use InputGuard\Guards\BoolGuard;
use InputGuard\Guards\FloatGuard;
use InputGuard\Guards\InstanceOfGuard;
use InputGuard\Guards\IntGuard;
use InputGuard\Guards\IterableGuard;
use InputGuard\Guards\StringGuard;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @var DefaultConfiguration
     */
    private static $configuration;

    public static function setUpBeforeClass()
    {
        self::$configuration = new DefaultConfiguration();
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
            [IterableGuard::class],
            [BoolGuard::class],
            [FloatGuard::class],
            [InstanceOfGuard::class],
            [IntGuard::class],
            [StringGuard::class],
        ];
    }
}
