<?php
declare(strict_types=1);

namespace InVal\Vals;

interface InputRetrievable
{
    /**
     * Return either the validated value or the default value.
     *
     * @return mixed
     */
    public function value();
}
