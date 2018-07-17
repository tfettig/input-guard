<?php
declare(strict_types=1);

namespace InVal;

interface Configurable
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    public function defaultValue(string $className);
}
