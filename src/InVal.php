<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\FloatVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
use InVal\Vals\Valadatable;

class InVal implements Valadatable
{
    /**
     * An array of objects that implement the Valadatable interface.
     *
     * @var array
     */
    private $vals = [];

    /**
     * @var Configurable
     */
    private $configuration;

    /**
     * Allows for injection of a Configurable object.
     *
     * @param Configurable $configuration
     */
    public function __construct(Configurable $configuration = null)
    {
        $this->configuration = $configuration ?? new Configuration();
    }

    /**
     * Remove all the vals that were setup but keep the configuration to allow for a prototype design pattern.
     */
    public function __clone()
    {
        $this->vals = [];
    }

    /**
     * Execute all validation object to determine success.
     *
     * @return bool
     */
    public function success(): bool
    {
        return array_reduce($this->vals, function (bool $success, Valadatable $val) {
            return $val->success() && $success;
        }, true);
    }

    /**
     * Return the current instance as the correct value.
     *
     * @return InVal
     */
    public function value(): InVal
    {
        $this->success();

        return $this;
    }

    /**
     * Allow for the injection of any Valadatable object.
     *
     * @param Valadatable $val
     */
    public function addVal(Valadatable $val): void
    {
        $this->vals[] = $val;
    }

    public function intVal($input): IntVal
    {
        $val = new IntVal($input, $this->configuration->defaultValue(IntVal::class));
        $this->addVal($val);

        return $val;
    }

    public function floatVal($input): FloatVal
    {
        $val = new FloatVal($input, $this->configuration->defaultValue(FloatVal::class));
        $this->addVal($val);

        return $val;
    }

    public function instanceOfVal($input, string $className): InstanceOfVal
    {
        $val = new InstanceOfVal($input, $className);
        $this->addVal($val);

        return $val;
    }
}
