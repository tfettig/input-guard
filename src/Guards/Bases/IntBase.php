<?php

declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait IntBase
{
    use Strict;

    /**
     * @var int
     */
    private $min = PHP_INT_MIN;

    /**
     * @var int
     */
    private $max = PHP_INT_MAX;

    public function between(int $min, int $max): self
    {
        $this->min = $min;
        $this->max = $max;

        return $this;
    }

    public function min(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function max(int $max): self
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

        if ($this->strict && \is_int($input) === false) {
            return false;
        }

        $options = [
            'options' => [
                'min_range' => PHP_INT_MIN,
                'max_range' => PHP_INT_MAX,
            ],
        ];

        $returned = filter_var($input, FILTER_VALIDATE_INT, $options);
        if ($returned === false) {
            return false;
        }

        if ($returned < $this->min || $returned > $this->max) {
            return false;
        }

        $value = $returned;

        return true;
    }
}
