<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\FloatBase;
use InputGuard\Guards\Bases\SingleInput;

/**
 * This validates floating point numbers.
 *
 * Please be aware that it currently does not handle very large and very small numbers well. Additional research into
 * PHP's handling of floating point handling needs to be done. It turns out that floating points for all computers are
 * hard. Who knew?
 */
class FloatGuard implements Guard
{
    use ErrorMessagesBase;
    use FloatBase;
    use SingleInput;

    public function value(): ?float
    {
        $this->success();

        return $this->value;
    }
}
