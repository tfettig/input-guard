<?php
declare(strict_types=1);

namespace InputGuard;

use InputGuard\Guards\Guard;

/**
 * All classes that implement the GuardChain must also be Guard class because:
 * 1) The API for the Guard and GuardChain actions should be the same.
 * 2) An GuardChain can be injected into other GuardChain.
 */
interface GuardChain extends Guard
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
