<?php
declare(strict_types=1);

namespace InVal\Vals;

/**
 * Base valid inputs:
 * 1) An empty string.
 * 2) A scalar (can be cast back and forth by PHP).
 *
 * Modifiable validations:
 * 1) Character length for the string.
 * 2) Regex on the string.
 */
class StringVal implements BuildableVal, StringValidatable
{
    use CompleteValTrait;
    use StringTrait;
    use SingleInputValidationTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraStringValidation($input): bool
    {
        // Short circuit for anything not a integer, float, string or boolean.
        return \is_scalar($input);
    }

    public function value(): ?string
    {
        $this->success();

        return $this->value;
    }
}
