<?php
declare(strict_types=1);

namespace InVal\Vals;

class ArrayVal implements CompleteVal
{
    use ErrorMessageTrait;
    use ValidateSingleInputTrait;

    /**
     * @var int
     */
    private $minSize = 0;

    /**
     * @var int|null;
     */
    private $maxSize;

    public function __construct($input, ?array $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    public function maxSize(int $max): self
    {
        $this->maxSize = $max;

        return $this;
    }

    public function minSize(int $min): self
    {
        $this->minSize = $min;

        return $this;
    }

    public function between(int $min, int $max): self
    {
        $this->minSize = $min;
        $this->maxSize = $max;

        return $this;
    }

    public function value(): ?array
    {
        $this->success();

        return $this->value;
    }

    protected function validation(): bool
    {
        if (!\is_array($this->input)) {
            return false;
        }

        $arraySize = count($this->input);
        if ($arraySize < $this->minSize || ($this->maxSize && $arraySize > $this->maxSize)) {
            return false;
        }

        $this->value = $this->input;

        return true;
    }
}
