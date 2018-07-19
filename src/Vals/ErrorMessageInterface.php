<?php
declare(strict_types=1);

namespace InVal\Vals;

interface ErrorMessageInterface
{
    public function errorMessage(string $message): CompleteVal;

    public function pullErrorMessages(): array;
}
