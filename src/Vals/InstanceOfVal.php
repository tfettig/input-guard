<?php
declare(strict_types=1);

namespace InVal\Vals;

class InstanceOfVal implements CompleteVal
{
    use ErrorMessageTrait;
    use ValidateSingleInputTrait;

    /**
     * @var string
     */
    private $className;

    public function __construct($input, string $className)
    {
        $this->className = $className;
        $this->input     = $input;
    }

    public function success(): bool
    {
        return $this->validate(function () {
            $success     = $this->input instanceof $this->className;
            $this->value = $success ? $this->input : $this->value;

            return $success;
        });
    }

    public function value(): ?object
    {
        $this->success();

        return $this->value;
    }
}
