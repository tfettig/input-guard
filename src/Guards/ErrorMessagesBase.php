<?php

declare(strict_types=1);

namespace InputGuard\Guards;

trait ErrorMessagesBase
{
    /**
     * @var array
     */
    private $errorMessages = [];

    abstract public function success(): bool;

    /**
     * @param string $message
     *
     * @return $this
     */
    public function errorMessage(string $message): self
    {
        $this->errorMessages[] = $message;

        return $this;
    }

    public function pullErrorMessages(): array
    {
        $this->success();

        return $this->errorMessages;
    }
}
