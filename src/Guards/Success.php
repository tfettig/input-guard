<?php
declare(strict_types=1);

namespace InputGuard\Guards;

interface Success
{
    /**
     * Validate the inputs for success.
     *
     * @return bool
     */
    public function success(): bool;
}
