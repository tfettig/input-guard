<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\BoolVal;
use InVal\Vals\FloatVal;
use InVal\Vals\InListVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
use InVal\Vals\IterableIntVal;
use InVal\Vals\IterableStringableVal;
use InVal\Vals\IterableStringVal;
use InVal\Vals\IterableVal;
use InVal\Vals\StringableVal;
use InVal\Vals\StringVal;

class Configuration implements Configurable
{
    /**
     * An array of default values for Valadatable objects that can be overwritten.
     *
     * @var array
     */
    protected $defaults = [
        BoolVal::class               => null,
        FloatVal::class              => null,
        InListVal::class             => null,
        InListVal::class . 'strict'  => true,
        InstanceOfVal::class         => null,
        IntVal::class                => null,
        IterableIntVal::class        => null,
        IterableStringableVal::class => null,
        IterableStringVal::class     => null,
        IterableVal::class           => null,
        StringableVal::class         => null,
        StringVal::class             => null,
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
