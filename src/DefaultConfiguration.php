<?php
declare(strict_types=1);

namespace InputGuard;

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

class DefaultConfiguration implements Configuration
{
    /**
     * An array of default values for Valadatable objects that can be overwritten.
     *
     * @var array
     */
    protected $defaults = [
        BoolGuard::class               => null,
        FloatGuard::class              => null,
        InListGuard::class             => null,
        InListGuard::class . 'strict'  => true,
        InstanceOfGuard::class         => null,
        IntGuard::class                => null,
        IterableFloatGuard::class      => null,
        IterableIntGuard::class        => null,
        IterableStringableGuard::class => null,
        IterableStringGuard::class     => null,
        IterableGuard::class           => null,
        StringableGuard::class         => null,
        StringGuard::class             => null,
    ];

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function defaultValue(string $className)
    {
        return $this->defaults[$className] ?? null;
    }
}
