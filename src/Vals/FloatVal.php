<?php
declare(strict_types=1);

namespace InVal\Vals;

class FloatVal implements CompleteVal
{
    use ErrorMessageTrait;
    use ValidateSingleInputTrait;

    public function __construct($input, ?float $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    public function success(): bool
    {
        return $this->validate(function () {
            $options = [
                'options' => [
                    'min_range' => PHP_FLOAT_MIN,
                    'max_range' => PHP_FLOAT_MAX,
                ],
            ];

            $value = filter_var($this->input, FILTER_VALIDATE_FLOAT, $options);
            if ($value === false) {
                return false;
            }

            $this->value = $value;

            return true;
        });
    }

    public function value(): ?float
    {
        $this->success();

        return $this->value;
    }
}
