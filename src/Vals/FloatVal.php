<?php
declare(strict_types=1);

namespace InVal\Vals;

/**
 * This validates floating point numbers.
 *
 * Please be aware that it currently does not handle very large and very small numbers well. Additional research into
 * PHP's handling of floating point handling needs to be done. It turns out that floating points for all computers are
 * hard.  Who knew?
 */
class FloatVal implements CompleteVal
{
    use CompleteValTrait;
    use ValidateSingleInputTrait;

    /**
     * @var float
     */
    private $min = PHP_FLOAT_MIN;

    /**
     * @var float
     */
    private $max = PHP_FLOAT_MAX;

    public function __construct($input, ?float $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    public function between(float $min, float $max): self
    {
        $this->min = $min;
        $this->max = $max;

        return $this;
    }

    public function min(float $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function max(float $max): self
    {
        $this->max = $max;

        return $this;
    }

    protected function validation(): bool
    {
        if (\is_bool($this->input)) {
            return false;
        }

        $value = filter_var($this->input, FILTER_VALIDATE_FLOAT);
        if ($value === false) {
            return false;
        }

        if ($value < $this->min || $value > $this->max) {
            return false;
        }

        $this->value = $value;

        return true;
    }

    public function value(): ?float
    {
        $this->success();

        return $this->value;
    }
}
