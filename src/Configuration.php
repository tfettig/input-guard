<?php
declare(strict_types=1);

namespace InputGuard;

interface Configuration
{
    /**
     * Allows for the configuration of the default value of invalid Guard objects.
     *
     * @param string $class
     *
     * @return mixed
     */
    public function defaultValue(string $class);

    /**
     * Sets the default strictness to strict for type comparison of inputs.
     *
     * @see http://php.net/manual/en/language.types.type-juggling.php
     *
     * @param string $class
     *
     * @return bool
     */
    public function defaultStrict(string $class = ''): bool;
}
