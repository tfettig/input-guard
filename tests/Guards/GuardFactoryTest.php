<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\DefaultConfiguration;
use InputGuard\Guards\BoolGuard;
use InputGuard\Guards\FailAlwaysGuard;
use InputGuard\Guards\FloatGuard;
use InputGuard\Guards\GuardFactory;
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
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @noinspection EfferentObjectCouplingInspection
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GuardFactoryTest extends TestCase
{
    /**
     * @dataProvider createProvider
     *
     * @param string $class
     * @param mixed  $input
     * @param array  $extra
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testCreate(string $class, $input, array $extra): void
    {
        $instance = new GuardFactory(new DefaultConfiguration());

        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf($class, $instance->create($class, $input, $extra));
    }

    /**
     * @return array[]
     */
    public function createProvider(): array
    {
        return [
            [BoolGuard::class, '', []],
            [IntGuard::class, '', []],
            [FloatGuard::class, '', []],
            [InstanceOfGuard::class, '', ['1']],
            [StringGuard::class, '', []],
            [StringableGuard::class, '', []],
            [IterableGuard::class, '', []],
            [IterableIntGuard::class, '', []],
            [IterableFloatGuard::class, '', []],
            [IterableStringGuard::class, '', []],
            [IterableStringableGuard::class, '', []],
            [InListGuard::class, '', [[]]],
            [FailAlwaysGuard::class, '', []],
        ];
    }
}
