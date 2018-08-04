<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\ArrayVal;
use InVal\Vals\BoolVal;
use InVal\Vals\CompleteVal;
use InVal\Vals\CompleteValTrait;
use InVal\Vals\FloatVal;
use InVal\Vals\InListVal;
use InVal\Vals\InstanceOfVal;
use InVal\Vals\IntVal;
use InVal\Vals\StringableVal;
use InVal\Vals\StringVal;

class InVal implements CompleteVal
{
    use CompleteValTrait;

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
        // Pass a local error messages variable to avoid merging arrays inside a loop.
        $error_messages = [];
        $success        = array_reduce($this->vals, function (bool $success, CompleteVal $val) use (&$error_messages) {
            // Check for success/failure for all collected Val's.
            if ($val->success() === false) {
                $error_messages[] = $val->pullErrorMessages();
                $success          = false;
            }

            return $success;
        }, true);

        // Merge arrays, remove duplicates, and reset the keys.
        $this->errorMessages = array_values(array_unique(array_merge($this->errorMessages, ...$error_messages)));

        return $success;
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
     * @param CompleteVal $val
     *
     * @return CompleteVal
     */
    public function addVal(CompleteVal $val): CompleteVal
    {
        $this->vals[] = $val;

        return $val;
    }

    public function boolVal($input): BoolVal
    {
        $val = new BoolVal($input, $this->configuration->defaultValue(BoolVal::class));
        $this->addVal($val);

        return $val;
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

    public function stringVal($input): StringVal
    {
        $val = new StringVal($input, $this->configuration->defaultValue(StringVal::class));
        $this->addVal($val);

        return $val;
    }

    public function stringableVal($input): StringableVal
    {
        $val = new StringableVal($input, $this->configuration->defaultValue(StringableVal::class));
        $this->addVal($val);

        return $val;
    }

    public function arrayVal($input): ArrayVal
    {
        $val = new ArrayVal($input, $this->configuration->defaultValue(ArrayVal::class));
        $this->addVal($val);

        return $val;
    }

    public function inListVal($input, iterable $list): InListVal
    {
        $val = new InListVal(
            $input,
            $list,
            $this->configuration->defaultValue(InListVal::class),
            $this->configuration->defaultValue(InListVal::class . 'strict')
        );
        $this->addVal($val);

        return $val;
    }
}
