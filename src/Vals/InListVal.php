<?php
declare(strict_types=1);

namespace InVal\Vals;

use ArrayObject;

class InListVal implements CompleteVal
{
    use CompleteValTrait;
    use SingleInputValidationTrait;

    /**
     * @var iterable
     */
    private $list;

    /**
     * @var bool
     */
    private $strict;

    /**
     * InListVal constructor.
     *
     * @param mixed    $input
     * @param iterable $list
     * @param null     $defaultValue
     * @param bool     $defaultStrict
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct($input, iterable $list, $defaultValue = null, bool $defaultStrict = true)
    {
        $this->input  = $input;
        $this->list   = $list;
        $this->value  = $defaultValue;
        $this->strict = $defaultStrict;
    }

    public function strict(): self
    {
        $this->strict = true;

        return $this;
    }

    public function nonStrict(): self
    {
        $this->strict = false;

        return $this;
    }

    public function value()
    {
        $this->success();

        return $this->value;
    }

    protected function validation(): bool
    {
        // If the input is an array or an ArrayObject then it's cheap to cast it and use in_array().
        if ((\is_array($this->list) || $this->list instanceof ArrayObject) &&
            \in_array($this->input, (array)$this->list, $this->strict)) {
            $this->value = $this->input;
            return true;
        }

        $validate = $this->strict
            ? function ($value) {
                return $value === $this->input;
            }
            : function ($value) {
                /** @noinspection TypeUnsafeComparisonInspection */
                return $value == $this->input;
            };

        // Since the type hint is 'iterable' (aka Traversable) iterate over the object.
        foreach ($this->list as $value) {
            if ($validate($value)) {
                $this->value = $this->input;
                return true;
            }
        }

        return false;
    }
}
