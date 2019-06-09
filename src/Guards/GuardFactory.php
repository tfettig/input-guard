<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Configuration;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GuardFactory
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param mixed $input
     *
     * @return BoolGuard
     */
    public function boolGuard($input): BoolGuard
    {
        return new BoolGuard($input, $this->defaultValue(BoolGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return IntGuard
     */
    public function intGuard($input): IntGuard
    {
        return new IntGuard($input, $this->defaultValue(IntGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return FloatGuard
     */
    public function floatGuard($input): FloatGuard
    {
        return new FloatGuard($input, $this->defaultValue(FloatGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return StringGuard
     */
    public function stringGuard($input): StringGuard
    {
        return new StringGuard($input, $this->defaultValue(StringGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return StringableGuard
     */
    public function stringableGuard($input): StringableGuard
    {
        return new StringableGuard($input, $this->defaultValue(StringableGuard::class));
    }

    /**
     * @param mixed  $input
     * @param string $className
     *
     * @return InstanceOfGuard
     */
    public function instanceOfGuard($input, string $className): InstanceOfGuard
    {
        return new InstanceOfGuard($input, $className, $this->defaultValue(InstanceOfGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return IterableGuard
     */
    public function iterableGuard($input): IterableGuard
    {
        return new IterableGuard($input, $this->defaultValue(IterableGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return IterableIntGuard
     */
    public function iterableIntGuard($input): IterableIntGuard
    {
        return new IterableIntGuard($input, $this->defaultValue(IterableIntGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return IterableFloatGuard
     */
    public function iterableFloatGuard($input): IterableFloatGuard
    {
        return new IterableFloatGuard($input, $this->defaultValue(IterableFloatGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return IterableStringGuard
     */
    public function iterableStringGuard($input): IterableStringGuard
    {
        return new IterableStringGuard($input, $this->defaultValue(IterableStringGuard::class));
    }

    /**
     * @param mixed $input
     *
     * @return IterableStringableGuard
     */
    public function iterableStringableGuard($input): IterableStringableGuard
    {
        return new IterableStringableGuard($input, $this->defaultValue(IterableStringableGuard::class));
    }

    /**
     * @param mixed    $input
     * @param iterable $list
     *
     * @return InListGuard
     */
    public function inListGuard($input, iterable $list): InListGuard
    {
        return new InListGuard(
            $input,
            $list,
            $this->defaultValue(InListGuard::class),
            $this->defaultStrict(InListGuard::class)
        );
    }

    /**
     * @return FailAlwaysGuard
     */
    public function failAlwaysGuard(): FailAlwaysGuard
    {
        return new FailAlwaysGuard($this->defaultValue(FailAlwaysGuard::class));
    }

    /**
     * @param string $class
     *
     * @return mixed
     */
    private function defaultValue(string $class)
    {
        return $this->configuration->defaultValue($class);
    }

    private function defaultStrict(string $class): bool
    {
        return $this->configuration->defaultStrict($class);
    }
}
