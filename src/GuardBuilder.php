<?php
declare(strict_types=1);

namespace InputGuard;

use InputGuard\Guards\Guard;

/**
 * All builder classes are also a Guard class because:
 * 1) The API for the guard and builder to do the same actions are the same.
 * 2) Builders can be injected into other builders.
 */
interface GuardBuilder extends Guard
{
    /**
     * Allow for property injection of any Guard object.
     *
     * @param Guard $val
     *
     * @return Guard
     */
    public function add(Guard $val): Guard;
}
