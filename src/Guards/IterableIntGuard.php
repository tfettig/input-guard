<?php

declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\IntBase;
use InputGuard\Guards\Bases\SingleIterableInput;

class IterableIntGuard implements Guard
{
    use ErrorMessagesBase;

    // Use the Iterable's validation as the primary validation logic and rename the Int validation method.
    use IntBase {
        SingleIterableInput::validation insteadof IntBase;
        IntBase::validation as intValidation;
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
        return $this->intValidation($element, $value);
    }
}
