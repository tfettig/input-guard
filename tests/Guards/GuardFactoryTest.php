<?php

declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\DefaultConfiguration;
use InputGuard\Guards\GuardFactory;
use PHPUnit\Framework\TestCase;

/**
 * @noinspection EfferentObjectCouplingInspection
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GuardFactoryTest extends TestCase
{
    /**
     * @var GuardFactory
     */
    private $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->factory = new GuardFactory(new DefaultConfiguration());
    }

    public function testBoolGuard(): void
    {
        $this->factory->boolGuard('');
        $this->addToAssertionCount(1);
    }

    public function testIntGuard(): void
    {
        $this->factory->intGuard('');
        $this->addToAssertionCount(1);
    }

    public function testFloatGuard(): void
    {
        $this->factory->floatGuard('');
        $this->addToAssertionCount(1);
    }

    public function testStringGuard(): void
    {
        $this->factory->stringGuard('');
        $this->addToAssertionCount(1);
    }

    public function testStringableGuard(): void
    {
        $this->factory->stringableGuard('');
        $this->addToAssertionCount(1);
    }

    public function testInstanceOfGuard(): void
    {
        $this->factory->instanceOfGuard('', '');
        $this->addToAssertionCount(1);
    }

    public function testIterableGuard(): void
    {
        $this->factory->iterableGuard('');
        $this->addToAssertionCount(1);
    }

    public function testIterableIntGuard(): void
    {
        $this->factory->iterableIntGuard('');
        $this->addToAssertionCount(1);
    }

    public function testIterableFloatGuard(): void
    {
        $this->factory->iterableFloatGuard('');
        $this->addToAssertionCount(1);
    }

    public function testIterableStringGuard(): void
    {
        $this->factory->iterableStringGuard('');
        $this->addToAssertionCount(1);
    }

    public function testIterableStringableGuard(): void
    {
        $this->factory->iterableStringableGuard('');
        $this->addToAssertionCount(1);
    }

    public function testInListGuard(): void
    {
        $this->factory->inListGuard('', []);
        $this->addToAssertionCount(1);
    }

    public function testFailAlwaysGuard(): void
    {
        $this->factory->failAlwaysGuard();
        $this->addToAssertionCount(1);
    }
}
