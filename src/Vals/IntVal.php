<?php
declare(strict_types=1);

namespace InVal\Vals;

class IntVal implements CompleteVal
{
    use CompleteValTrait;
    use SingleInputValidationTrait;

    /**
     * @var int
     */
    private $min = PHP_INT_MIN;

    /**
     * @var int
     */
    private $max = PHP_INT_MAX;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

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

    protected function validation(): bool
    {
        if (\is_bool($this->input)) {
            return false;
        }

        $options = [
            'options' => [
                'min_range' => PHP_INT_MIN,
                'max_range' => PHP_INT_MAX,
            ],
        ];

        $value = filter_var($this->input, FILTER_VALIDATE_INT, $options);
        if ($value === false) {
            return false;
        }

        if ($value < $this->min || $value > $this->max) {
            return false;
        }

        $this->value = $value;

        return true;
    }

    public function value(): ?int
    {
        $this->success();

        return $this->value;
    }
}
