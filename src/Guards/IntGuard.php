<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\IntBase;
use InputGuard\Guards\Bases\SingleInput;

class IntGuard implements Guard
{
    use ErrorMessagesBase;
    use IntBase;
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

    public function value(): ?int
    {
        $this->success();

        return $this->value;
    }
}
