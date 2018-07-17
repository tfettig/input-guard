<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\IntVal;

class Configuration implements Configurable
{
    /**
     * An array of default values for Valadatable objects that can be overwritten.
     *
     * @var array
     */
    protected $defaults = [
        'int' => null,
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
                return $this->defaults['int'] ?? null;

            default:
                return null;
        }
    }
}
