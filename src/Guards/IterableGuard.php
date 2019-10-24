<?php

declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\SingleIterableInput;

class IterableGuard implements Guard
{
    use ErrorMessagesBase;
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
        $value = $element;
        return true;
    }
}
