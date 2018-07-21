<?php
declare(strict_types=1);

namespace InVal\Vals;

trait ErrorMessageTrait
{
    /**
     * @var array
     */
    private $errorMessages = [];

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param string $message
     *
     * @return $this
     */
    public function errorMessage(string $message)
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
