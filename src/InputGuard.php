<?php
declare(strict_types=1);

namespace InputGuard;

use InputGuard\Guards\BoolGuard;
use InputGuard\Guards\ErrorMessagesBase;
use InputGuard\Guards\FloatGuard;
use InputGuard\Guards\Guard;
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

/** @noinspection EfferentObjectCouplingInspection */

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InputGuard implements GuardChain
{
    use ErrorMessagesBase;

    /**
     * @var Guard[]
     */
    private $guards = [];

    /**
     * @var bool|\null
     */
    private $validated;

    /**
     * @var GuardFactory
     */
    private $factory;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration = null)
    {
        $this->factory = new GuardFactory($configuration ?? new DefaultConfiguration());
    }

    public function __clone()
    {
        $this->errorMessages = [];
        $this->guards        = [];
        $this->validated     = null;
    }

    /**
     * @param mixed $input
     *
     * @return BoolGuard
     */
    public function bool($input): BoolGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(BoolGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return IntGuard
     */
    public function int($input): IntGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(IntGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return FloatGuard
     */
    public function float($input): FloatGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(FloatGuard::class, $input);
    }

    /**
     * @param mixed  $input
     * @param string $className
     *
     * @return InstanceOfGuard
     */
    public function instanceOf($input, string $className): InstanceOfGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(InstanceOfGuard::class, $input, $className);
    }

    /**
     * @param mixed $input
     *
     * @return StringGuard
     */
    public function string($input): StringGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(StringGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return StringableGuard
     */
    public function stringable($input): StringableGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(StringableGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return IterableGuard
     */
    public function array($input): IterableGuard
    {
        return $this->iterable($input);
    }

    /**
     * @param mixed $input
     *
     * @return IterableGuard
     */
    public function iterable($input): IterableGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(IterableGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return IterableIntGuard
     */
    public function iterableInt($input): IterableIntGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(IterableIntGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return IterableFloatGuard
     */
    public function iterableFloat($input): IterableFloatGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(IterableFloatGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return IterableStringGuard
     */
    public function iterableString($input): IterableStringGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(IterableStringGuard::class, $input);
    }

    /**
     * @param mixed $input
     *
     * @return IterableStringableGuard
     */
    public function iterableStringable($input): IterableStringableGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(IterableStringableGuard::class, $input);
    }

    /**
     * @param mixed    $input
     * @param iterable $list
     *
     * @return InListGuard
     */
    public function inList($input, iterable $list): InListGuard
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->createGuard(InListGuard::class, $input, $list);
    }

    public function success(): bool
    {
        if ($this->validated !== null) {
            return $this->validated;
        }

        // Pass a local error messages variable to avoid merging arrays inside a loop.
        $error_messages = [];
        $success        = array_reduce(
            $this->guards,
            static function (bool $success, Guard $guard) use (&$error_messages): bool {
                // Check for success/failure for all collected Val's.
                if ($guard->success() === false) {
                    $error_messages[] = $guard->pullErrorMessages();
                    $success          = false;
                }

                return $success;
            },
            true
        );

        if ($error_messages) {
            // Merge the errors, remove duplicates, and reset the keys.
            $this->errorMessages = array_values(array_unique(array_merge($this->errorMessages, ...$error_messages)));
        }

        $this->validated = $success;

        return $success;
    }

    public function value(): InputGuard
    {
        return $this;
    }

    public function add(Guard $val): Guard
    {
        $this->validated = null;
        $this->guards[]  = $val;

        return $val;
    }

    private function createGuard(string $class, $input, ...$extra): Guard
    {
        return $this->add($this->factory->create($class, $input, $extra));
    }
}
