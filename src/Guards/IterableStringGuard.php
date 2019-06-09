<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\SingleIterableInput;
use InputGuard\Guards\Bases\StringBase;

/**
 * Base valid inputs:
 * 1) An empty iterable.
 * 2) An iterable of scalars (can be cast back and forth by PHP).
 *
 * Modifiable validations:
 * 1) Number of elements in the iterable.
 * 2) Character length for each element.
 * 3) Regex on each element.
 */
class IterableStringGuard implements Guard
{
    use ErrorMessagesBase;
    use SingleIterableInput;
    use StringBase {
        // Use the iterable's validation as the primary validation logic and rename the string validation method.
        SingleIterableInput::validation insteadof StringBase;
        StringBase::validation as stringValidation;
    }

    /**
     * @param mixed         $input
     * @param iterable|null $default
     */
    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validationShortCircuit($input): bool
    {
        // Short circuit for anything not a integer, float, string or boolean.
        return \is_scalar($input);
    }

    protected function validateIterableElement($element, &$value): bool
    {
        return $this->stringValidation($element, $value);
    }
}
