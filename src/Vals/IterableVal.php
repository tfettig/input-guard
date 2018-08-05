<?php
declare(strict_types=1);

namespace InVal\Vals;

use Traversable;

class IterableVal implements CompleteVal
{
    use CompleteValTrait;
    use ValidateSingleInputTrait;

    /**
     * @var int
     */
    private $minSize = 0;

    /**
     * @var int|null;
     */
    private $maxSize;

    public function __construct($input, ?iterable $default = null)
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

    public function value(): ?iterable
    {
        $this->success();

        return $this->value;
    }

    public function valueAsArray(): array
    {
        $this->success();

        return $this->value instanceof Traversable ? iterator_to_array($this->value) : (array)$this->value;
    }

    protected function validation(): bool
    {
        if (!\is_array($this->input) && !$this->input instanceof Traversable) {
            return false;
        }

        if (\is_array($this->input)) {
            $iterableSize = count($this->input);
        } else {
            $iterableSize = iterator_count($this->input);
        }

        if ($iterableSize < $this->minSize || ($this->maxSize && $iterableSize > $this->maxSize)) {
            return false;
        }

        $this->value = $this->input;

        return true;
    }
}
