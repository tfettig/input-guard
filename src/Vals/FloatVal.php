<?php
declare(strict_types=1);

namespace InVal\Vals;

/**
 * This validates floating point numbers.
 *
 * Please be aware that it currently does not handle very large and very small numbers well. Additional research into
 * PHP's handling of floating point handling needs to be done. It turns out that floating points for all computers are
 * hard. Who knew?
 */
class FloatVal implements BuildableVal
{
    use BuildableValTrait;
    use FloatTrait;
    use SingleInputValidationTrait;

    public function value(): ?float
    {
        $this->success();

        return $this->value;
    }
}
