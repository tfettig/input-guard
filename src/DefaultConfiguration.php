<?php

declare(strict_types=1);

namespace InputGuard;

use InputGuard\Guards\InListGuard;

class DefaultConfiguration implements Configuration
{
    /**
     * @var string[] Key: A fully qualified class name of a valadatable object. Value: The default value.
     */
    protected $defaultValues = [];

    /**
     * @var bool
     */
    protected $defaultStrictTypeComparision = false;

    /**
     * @var bool[]
     */
    protected $strictTypeComparisionOverride = [
        InListGuard::class => true,
    ];

    public function defaultValue(string $class)
    {
        return $this->defaultValues[$class] ?? null;
    }

    public function defaultStrict(string $class = ''): bool
    {
        return $this->strictTypeComparisionOverride[$class] ?? $this->defaultStrictTypeComparision;
    }
}
