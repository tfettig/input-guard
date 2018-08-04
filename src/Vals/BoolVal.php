<?php
declare(strict_types=1);

namespace InVal\Vals;

class BoolVal implements CompleteVal
{
    use CompleteValTrait;
    use ValidateSingleInputTrait;

    /**
     * @var bool
     */
    private $pseudoBools = false;

    public function __construct($input, ?bool $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    public function allowPseudoBools(): self
    {
        $this->pseudoBools = true;

        return $this;
    }

    public function value(): ?bool
    {
        $this->success();

        return $this->value;
    }

    protected function validation(): bool
    {
        if ($this->input === true || ($this->pseudoBools && \in_array($this->input, [1, '1'], true))) {
            $this->value = true;
            return true;
        }

        if ($this->input === false || ($this->pseudoBools && \in_array($this->input, [0, '0', ''], true))) {
            $this->value = false;
            return true;
        }

        return false;
    }
}
