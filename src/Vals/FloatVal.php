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
    use SingleInputValidationTrait;

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

    protected function validation($input, &$value): bool
    {
        if (\is_bool($input)) {
            return false;
        }

        $return = filter_var($input, FILTER_VALIDATE_FLOAT);
        if ($return === false) {
            return false;
        }

        if ($return < $this->min || $return > $this->max) {
            return false;
        }

        $value = $return;

        return true;
    }

    public function value(): ?float
    {
        $this->success();

        return $this->value;
    }
}
