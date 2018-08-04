<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\ArrayVal;
use InVal\Vals\BoolVal;
use InVal\Vals\FloatVal;
use InVal\Vals\InListVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
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
        ArrayVal::class             => null,
        BoolVal::class              => null,
        FloatVal::class             => null,
        InstanceOfVal::class        => null,
        IntVal::class               => null,
        StringVal::class            => null,
        InListVal::class            => null,
        InListVal::class . 'strict' => true,
    ];

    /**
     * @param string $className
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function defaultValue(string $className)
    {
        switch ($className) {
            case ArrayVal::class:
                return $this->defaults[$className] ?? null;
            case BoolVal::class:
                return $this->defaults[$className] ?? null;
            case IntVal::class:
                return $this->defaults[$className] ?? null;
            case FloatVal::class:
                return $this->defaults[$className] ?? null;
            case InListVal::class:
                return $this->defaults[$className] ?? null;
            case InListVal::class . 'strict':
                return $this->defaults[$className . 'strict'] ?? true;
            case InstanceOfVal::class:
                return $this->defaults[$className] ?? null;
            case StringVal::class:
                return $this->defaults[$className] ?? null;
            case StringableVal::class:
                return $this->defaults[$className] ?? null;
            default:
                return null;
        }
    }
}
