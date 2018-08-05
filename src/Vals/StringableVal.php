<?php
declare(strict_types=1);

namespace InVal\Vals;

class StringableVal implements CompleteVal, StringValidatable
{
    use CompleteValTrait;
    use StringTrait;
    use SingleInputValidationTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validation(): bool
    {
        // Short circuit for anything not a integer, float, string or boolean.
        if (\is_scalar($this->input) === false &&
            \is_object($this->input) && method_exists($this->input, '__toString') === false) {
            return false;
        }

        if ($this->stringValidation((string)$this->input) === false) {
            return false;
        }

        $this->value = \is_scalar($this->input) ? (string)$this->input : $this->input;

        return true;
    }

    /**
     * @return null|string|object
     */
    public function value()
    {
        $this->success();

        return $this->value;
    }
}
