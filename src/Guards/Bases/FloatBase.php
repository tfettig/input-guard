<?php
declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait FloatBase
{
    /**
     * @var float
     */
    private $min = PHP_FLOAT_MIN;

    /**
     * @var float
     */
    private $max = PHP_FLOAT_MAX;

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
}
