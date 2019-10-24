<?php

declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\SingleInput;
use InputGuard\Guards\Bases\StringBase;

/**
 * Base valid inputs:
 * 1) An empty string.
 * 2) A scalar (can be cast back and forth by PHP), and a class with a __toString() method.
 *
 * Modifiable validations:
 * 1) Character length for the string.
 * 2) Regex on the string.
 */
class StringableGuard implements Guard
{
    use ErrorMessagesBase;
    use StringBase;
    use SingleInput;

    /**
     * @param mixed    $input
     * @param int|null $default
     */
    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validationShortCircuit($input): bool
    {
        // The is_scalar is a short circuit for anything not a integer, float, string, boolean.
        if ($this->strict ? \is_string($input) : \is_scalar($input)) {
            return true;
        }

        return (\is_object($input) && method_exists($input, '__toString'));
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
