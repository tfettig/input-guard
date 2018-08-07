<?php
declare(strict_types=1);

namespace InputGuard\Guards;

interface Value
{
    /**
     * Return either the validated value or the default value.
     *
     * @return mixed
     */
    public function value();
}
