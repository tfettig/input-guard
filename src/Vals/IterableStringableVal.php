<?php
declare(strict_types=1);

namespace InVal\Vals;

/**
 * Base valid inputs:
 * 1) An empty iterable.
 * 2) An iterable of scalars (can be cast back and forth by PHP), and a class with a __toString() method.
 *
 * Modifiable validations:
 * 1) Number of elements in the iterable.
 * 2) Character length for each element.
 * 3) Regex on each element.
 */
class IterableStringableVal implements BuildableVal
{
    use CompleteValTrait;
    use SingleInputIterableValidationTrait;
    use StringTrait {
        // Use the iterable's validation as the primary validation logic and rename the string validation method.
        SingleInputIterableValidationTrait::validation insteadof StringTrait;
        StringTrait::validation as stringValidation;
    }

    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraStringValidation($input): bool
    {
        // Short circuit for anything not a integer, float, string, boolean, or object with a __toString method.
        return \is_scalar($input) || (\is_object($input) && method_exists($input, '__toString'));
    }

    protected function extraIterableValidation(iterable $input): bool
    {
        foreach ($input as $item) {
            $value = null;
            if ($this->stringValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}