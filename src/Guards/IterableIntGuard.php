<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\IntBase;
use InputGuard\Guards\Bases\SingleIterableInput;

class IterableIntGuard implements Guard
{
    use ErrorMessagesBase;
    use IntBase {
        // Use the Iterable's validation as the primary validation logic and rename the Int validation method.
        SingleIterableInput::validation insteadof IntBase;
        IntBase::validation as intValidation;
    }
    use SingleIterableInput;

    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraIterableValidation(iterable $input): bool
    {
        foreach ($input as $item) {
            $value = null;
            if ($this->intValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}
