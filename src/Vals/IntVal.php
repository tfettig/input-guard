<?php
declare(strict_types=1);

namespace InVal\Vals;

class IntVal implements CompleteVal
{
    use ErrorMessageTrait;
    use ValidateSingleInputTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validation(): bool
    {
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

        $this->value = $value;

        return true;
    }

    public function value(): ?int
    {
        $this->success();

        return $this->value;
    }
}
