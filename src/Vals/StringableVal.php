<?php
declare(strict_types=1);

namespace InVal\Vals;

/**
 * Base valid inputs:
 * 1) An empty string.
 * 2) A scalar (can be cast back and forth by PHP), and a class with a __toString() method.
 *
 * Modifiable validations:
 * 1) Character length for the string.
 * 2) Regex on the string.
 */
class StringableVal implements BuildableVal, StringValidatable
{
    use BuildableValTrait;
    use StringTrait;
    use SingleInputValidationTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraStringValidation($input): bool
    {
        // Short circuit for anything not a integer, float, string, boolean, or object with a __toString method.
        return \is_scalar($input) || (\is_object($input) && method_exists($input, '__toString'));
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
