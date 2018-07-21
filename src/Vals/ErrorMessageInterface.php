<?php
declare(strict_types=1);

namespace InVal\Vals;

interface ErrorMessageInterface
{
    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param string $message
     *
     * @return $this
     */
    public function errorMessage(string $message);

    public function pullErrorMessages(): array;
}
