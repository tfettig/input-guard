<?php
declare(strict_types=1);

namespace InVal\Vals;

trait ErrorMessageTrait
{
    /**
     * @var array
     */
    private $errorMessages = [];

    public function errorMessage(string $message): CompleteVal
    {
        $this->errorMessages[] = $message;

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this;
    }

    public function pullErrorMessages(): array
    {
        return $this->errorMessages;
    }
}
