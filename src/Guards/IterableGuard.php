<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\SingleIterableInput;

class IterableGuard implements Guard
{
    use ErrorMessagesBase;
    use SingleIterableInput;

    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    /**
     * @param iterable $input
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function extraIterableValidation(/** @scrutinizer ignore-unused */ iterable $input): bool
    {
        return true;
    }
}
