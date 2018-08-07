<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\FloatBase;
use InputGuard\Guards\Bases\SingleIterableInput;

class IterableFloatGuard implements Guard
{
    use ErrorMessagesBase;
    use FloatBase {
        // Use the Iterable's validation as the primary validation logic and rename the float validation method.
        SingleIterableInput::validation insteadof FloatBase;
        FloatBase::validation as floatValidation;
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
            if ($this->floatValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}
