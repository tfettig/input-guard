<?php
declare(strict_types=1);

namespace InVal\Vals;

class StringVal implements CompleteVal, StringValidatable
{
    use CompleteValTrait;
    use StringTrait;
    use ValidateSingleInputTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validation(): bool
    {
        // Short circuit for anything not a integer, float, string or boolean.
        if (\is_scalar($this->input) === false) {
            return false;
        }

        $input = (string)$this->input;
        if ($this->stringValidation($input) === false) {
            return false;
        }

        $this->value = $input;

        return true;
    }

    public function value(): ?string
    {
        $this->success();

        return $this->value;
    }
}
