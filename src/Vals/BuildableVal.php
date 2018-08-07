<?php
declare(strict_types=1);

namespace InVal\Vals;

/**
 * Any object implementing this interface can added to the validator.
 */
interface BuildableVal extends Valadatable, InputRetrievable, ErrorMessageInterface
{
}
