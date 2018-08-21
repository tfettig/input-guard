<?php
declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait Strict
{
    /**
     * @var bool
     */
    private $strict = false;

    public function strict(): self
    {
        $this->strict = true;

        return $this;
    }

    public function nonStrict(): self
    {
        $this->strict = false;

        return $this;
    }
}
