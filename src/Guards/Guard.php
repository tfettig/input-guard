<?php

declare(strict_types=1);

namespace InputGuard\Guards;

/**
 * Any object implementing this interface can added to the InputGuard.
 */
interface Guard extends Success, Value, ErrorMessages
{
}
