<?php
declare(strict_types=1);

namespace InVal\Vals;

use Traversable;

trait SingleInputIterableValidationTrait
{
    use SingleInputValidationTrait;

    /**
     * @var int
     */
    private $minCount = 0;

    /**
     * @var int|null;
     */
    private $maxCount;

    /**
     * A method to allow extra validation to be done for iterables.
     *
     * @param iterable $input
     *
     * @return bool
     */
    abstract protected function extraIterableValidation(iterable $input): bool;

    public function maxCount(int $max): self
    {
        $this->maxCount = $max;

        return $this;
    }

    public function minCount(int $min): self
    {
        $this->minCount = $min;

        return $this;
    }

    public function betweenCount(int $min, int $max): self
    {
        $this->minCount = $min;
        $this->maxCount = $max;

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

    protected function validation($input, &$value): bool
    {
        if (!\is_array($input) && !$input instanceof Traversable) {
            return false;
        }

        if (\is_array($input)) {
            $iterableSize = count($input);
        } else {
            $iterableSize = iterator_count($input);
        }

        if ($iterableSize < $this->minCount || ($this->maxCount && $iterableSize > $this->maxCount)) {
            return false;
        }

        if ($this->extraIterableValidation($this->input) === false) {
            return false;
        }

        $value = $input;

        return true;
    }
}
