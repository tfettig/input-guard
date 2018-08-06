<?php
declare(strict_types=1);

namespace InVal\Vals;

class StringableVal implements CompleteVal, StringValidatable
{
    use CompleteValTrait;
    use StringTrait {
        SingleInputValidationTrait::validation insteadof StringTrait;
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
        // Short circuit for anything not a integer, float, string, boolean, or object with a __toString method.
        if (\is_scalar($input) === false &&
            \is_object($input) && method_exists($input, '__toString') === false) {
            return false;
        }

        if ($this->stringValidation($input, $value) === false) {
            return false;
        }

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
