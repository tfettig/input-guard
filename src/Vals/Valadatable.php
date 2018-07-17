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

    /**
     * Return either the validated value or the default value.
     *
     * @return mixed
     */
    public function value();
}
