<?php
declare(strict_types=1);

namespace InputGuardTests;

use InputGuard\DefaultConfiguration;
use InputGuard\Guards\BoolGuard;
use InputGuard\Guards\FloatGuard;
use InputGuard\Guards\InListGuard;
use InputGuard\Guards\InstanceOfGuard;
use InputGuard\Guards\IntGuard;
use InputGuard\Guards\IterableFloatGuard;
use InputGuard\Guards\IterableGuard;
use InputGuard\Guards\IterableIntGuard;
use InputGuard\Guards\IterableStringableGuard;
use InputGuard\Guards\IterableStringGuard;
use InputGuard\Guards\StringableGuard;
use InputGuard\Guards\StringGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/** @noinspection EfferentObjectCouplingInspection */

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DefaultConfigurationTest extends TestCase
{
    /**
     * @var DefaultConfiguration
     */
    private $configuration;

    public function setUp()
    {
        $this->configuration = new class() extends DefaultConfiguration
        {
            public function changeDefaultValue(string $class): void
            {
                $this->defaultValues[$class] = true;
            }

            public function changeStrictDefault(string $class): void
            {
                $this->strictTypeComparisionOverride[$class] = isset($this->strictTypeComparisionOverride[$class])
                    ? !$this->strictTypeComparisionOverride[$class]
                    : !$this->defaultStrictTypeComparision;
            }
        };
    }

    /**
     * @dataProvider valueProvider
     *
     * @param string $class
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDefaultValue(string $class, string $message): void
    {
        self::assertNull($this->configuration->defaultValue($class), $message);
    }

    /**
     * @dataProvider valueProvider
     *
     * @param string $class
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDefaultValueChanged(string $class, string $message): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->configuration->changeDefaultValue($class);

        self::assertTrue($this->configuration->defaultValue($class), $message);
    }

    public function valueProvider(): array
    {
        return [
            ['', 'Empty String'],
            ['nonexistent', 'Does not exist'],
            [BoolGuard::class, 'BoolGuard::class'],
            [FloatGuard::class, 'FloatGuard::class'],
            [InListGuard::class, 'InListGuard::class'],
            [InstanceOfGuard::class, 'InstanceOfGuard::class'],
            [IntGuard::class, 'IntGuard::class'],
            [IterableFloatGuard::class, 'IterableFloatGuard::class'],
            [IterableIntGuard::class, 'IterableIntGuard::class'],
            [IterableStringableGuard::class, 'IterableStringableGuard::class'],
            [IterableStringGuard::class, 'IterableStringGuard::class'],
            [IterableGuard::class, 'IterableGuard::class'],
            [StringableGuard::class, 'StringableGuard::class'],
            [StringGuard::class, 'StringGuard::class'],
        ];
    }

    /**
     * @dataProvider strictProvider
     *
     * @param string $class
     * @param bool   $expected_return
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDefaultStrict(string $class, bool $expected_return, string $message): void
    {
        self::assertSame($expected_return, $this->configuration->defaultStrict($class), $message);
    }

    /**
     * @dataProvider strictProvider
     *
     * @param string $class
     * @param bool   $expected_return
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDefaultStrictChanged(string $class, bool $expected_return, string $message): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->configuration->changeStrictDefault($class);

        self::assertSame(!$expected_return, $this->configuration->defaultStrict($class), $message);
    }

    public function strictProvider(): array
    {
        return [
            [BoolGuard::class, false, 'BoolGuard::class'],
            [FloatGuard::class, false, 'FloatGuard::class'],
            [InListGuard::class, true, 'InListGuard::class'],
            [InstanceOfGuard::class, false, 'InstanceOfGuard::class'],
            [IntGuard::class, false, 'IntGuard::class'],
            [IterableFloatGuard::class, false, 'IterableFloatGuard::class'],
            [IterableIntGuard::class, false, 'IterableIntGuard::class'],
            [IterableStringableGuard::class, false, 'IterableStringableGuard::class'],
            [IterableStringGuard::class, false, 'IterableStringGuard::class'],
            [IterableGuard::class, false, 'IterableGuard::class'],
            [StringableGuard::class, false, 'StringableGuard::class'],
            [StringGuard::class, false, 'StringGuard::class'],
        ];
    }
}
