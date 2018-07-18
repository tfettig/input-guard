<?php
declare(strict_types=1);

namespace InVal\Vals;

trait ErrorMessageTrait
{
    /**
     * @var array
     */
    private $errorMessages = [];

    public function errorMessage(string $message): void
    {
        $this->errorMessages[] = $message;
    }

    public function pullErrorMessages(): array
    {
        return $this->errorMessages;
    }
}
