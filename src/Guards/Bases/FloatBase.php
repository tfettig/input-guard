<?php

declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait FloatBase
{
    use Strict;

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

    /**
     * @param mixed $input
     * @param mixed $value
     *
     * @return bool
     */
    protected function validation($input, &$value): bool
    {
        if (\is_bool($input)) {
            return false;
        }

        if ($this->strict) {
            $return = \is_float($input) ? $input : false;
        } else {
            $return = filter_var($input, FILTER_VALIDATE_FLOAT);
        }

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
