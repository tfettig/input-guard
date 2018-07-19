<?php
declare(strict_types=1);

namespace InVal\Vals;

interface Valadatable
{
    /**
     * Validate the inputs for success.
     *
     * @return bool
     */
    public function success(): bool;
}
