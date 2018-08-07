<?php
declare(strict_types=1);

namespace InputGuard;

interface Configuration
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    public function defaultValue(string $className);
}
