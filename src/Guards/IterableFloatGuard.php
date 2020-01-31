<?php

declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\FloatBase;
use InputGuard\Guards\Bases\SingleIterableInput;

class IterableFloatGuard implements Guard
{
    use ErrorMessagesBase;

    // Use the Iterable's validation as the primary validation logic and rename the float validation method.
    use FloatBase {
        SingleIterableInput::validation insteadof FloatBase;
        FloatBase::validation as floatValidation;
    }
    use SingleIterableInput;

    /**
     * @param mixed         $input
     * @param iterable|null $default
     */
    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function validateIterableElement($element, &$value): bool
    {
        return $this->floatValidation($element, $value);
    }
}
