<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\FloatVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;

class Configuration implements Configurable
{
    /**
     * An array of default values for Valadatable objects that can be overwritten.
     *
     * @var array
     */
    protected $defaults = [
        FloatVal::class      => null,
        InstanceOfVal::class => null,
        IntVal::class        => null,
    ];

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function defaultValue(string $className)
    {
        switch ($className) {
            case IntVal::class:
                return $this->defaults[$className] ?? null;
            case FloatVal::class:
                return $this->defaults[$className] ?? null;
            case InstanceOfVal::class:
                return $this->defaults[$className] ?? null;
            default:
                return null;
        }
    }
}
