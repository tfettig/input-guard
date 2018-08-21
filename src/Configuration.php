<?php
declare(strict_types=1);

namespace InputGuard;

interface Configuration
{
    /**
     * Allows for the configuration of the default value of invalid Guard objects.
     *
     * @param string $className
     *
     * @return mixed
     */
    public function defaultValue(string $className);

    /**
     * Sets the default strictness to strict for type comparison of inputs.
     *
     * @see http://php.net/manual/en/language.types.type-juggling.php
     *
     * @return bool
     */
    public function defaultStrict(): bool;
}
