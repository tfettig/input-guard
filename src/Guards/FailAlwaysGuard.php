<?php
declare(strict_types=1);

namespace InputGuard\Guards;

class FailAlwaysGuard implements Guard
{
    use ErrorMessagesBase;

    /**
     * @var bool|\null
     */
    private $value;

    public function __construct(?bool $default = null)
    {
        $this->value = $default;
    }

    public function success(): bool
    {
        return false;
    }

    public function value(): ?bool
    {
        return $this->value;
    }
}
