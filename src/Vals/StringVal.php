<?php
declare(strict_types=1);

namespace InVal\Vals;

class StringVal implements CompleteVal, StringValidatable
{
    use CompleteValTrait;
    use StringTrait {
        StringTrait::validation as stringValidation;
    }
    use SingleInputValidationTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validation($input, &$value): bool
    {
        // Short circuit for anything not a integer, float, string or boolean.
        if (\is_scalar($input) === false) {
            return false;
        }

        $input = (string)$input;
        if ($this->stringValidation($input, $value) === false) {
            return false;
        }

        $value = $input;

        return true;
    }

    public function value(): ?string
    {
        $this->success();

        return $this->value;
    }
}
