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
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @param string $class
     * @param mixed  $input
     * @param array  $extra
     *
     * @return Guard
     */
    public function create(string $class, $input, array $extra): Guard
    {
        switch ($class) {
            case BoolGuard::class:
                $guard = new BoolGuard($input, $this->defaultValue(BoolGuard::class));
                break;
            case IntGuard::class:
                $guard = new IntGuard($input, $this->defaultValue(IntGuard::class));
                break;
            case FloatGuard::class:
                $guard = new FloatGuard($input, $this->defaultValue(FloatGuard::class));
                break;
            case InstanceOfGuard::class:
                $guard = new InstanceOfGuard($input, $extra[0], $this->defaultValue(InstanceOfGuard::class));
                break;
            case StringGuard::class:
                $guard = new StringGuard($input, $this->defaultValue(StringGuard::class));
                break;
            case StringableGuard::class:
                $guard = new StringableGuard($input, $this->defaultValue(StringableGuard::class));
                break;
            case IterableGuard::class:
                $guard = new IterableGuard($input, $this->defaultValue(IterableGuard::class));
                break;
            case IterableIntGuard::class:
                $guard = new IterableIntGuard($input, $this->defaultValue(IterableIntGuard::class));
                break;
            case IterableFloatGuard::class:
                $guard = new IterableFloatGuard($input, $this->defaultValue(IterableFloatGuard::class));
                break;
            case IterableStringGuard::class:
                $guard = new IterableStringGuard($input, $this->defaultValue(IterableStringGuard::class));
                break;
            case IterableStringableGuard::class:
                $guard = new IterableStringableGuard($input, $this->defaultValue(IterableStringableGuard::class));
                break;
            case InListGuard::class:
                $guard = new InListGuard(
                    $input,
                    $extra[0],
                    $this->defaultValue(InListGuard::class),
                    $this->defaultStrict(InListGuard::class)
                );
                break;
            default:
                $guard = new FailAlwaysGuard($this->defaultValue(FailAlwaysGuard::class));
                break;
        }

        return $guard;
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
