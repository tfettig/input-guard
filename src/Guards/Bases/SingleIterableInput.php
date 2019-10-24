<?php

declare(strict_types=1);

namespace InputGuard\Guards\Bases;

use ArrayAccess;
use Traversable;

trait SingleIterableInput
{
    use SingleInput;

    /**
     * @var int
     */
    private $minCount = 0;

    /**
     * @var int|\null
     */
    private $maxCount;

    /**
     * @var bool
     */
    private $elementsAreNullable = false;

    /**
     * Do validation for each for each element of an iterable.
     *
     * @param mixed $element
     * @param mixed $value
     *
     * @return bool
     */
    abstract protected function validateIterableElement($element, &$value): bool;

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

    public function elementsNullable(): self
    {
        $this->elementsAreNullable = true;

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

    /**
     * @param mixed $input
     * @param mixed $value
     *
     * @return bool
     */
    protected function validation($input, &$value): bool
    {
        if (is_iterable($input) === false) {
            return false;
        }

        return $this->validationForIterableInput($input, $value);
    }

    /**
     * @param iterable $input
     * @param mixed    $value
     *
     * @return bool
     */
    private function validationForIterableInput(iterable $input, &$value): bool
    {
        if ($this->isBetweenCountSize($input) === false) {
            return false;
        }

        foreach ($input as $key => $element) {
            if ($element === null) {
                if ($this->elementsAreNullable) {
                    continue;
                }

                return false;
            }

            $element_value = null;
            if ($this->validateIterableElement($element, $element_value) === false) {
                return false;
            }

            if (\is_array($input) || $input instanceof ArrayAccess) {
                // At this time only iterable that are arrays or implement array access can be modified.
                // I need to look into using http://www.php.net/manual/en/closure.bind.php, Refection, or other hacks
                // to see if it's possible to modify the elements of an object that only implements the Iterator
                // interface.

                /** @noinspection OffsetOperationsInspection */
                $input[$key] = $element_value;
            }

            // @todo Make the method return false if strict validation is disabled and the iterable cannot be updated.
        }

        $value = $input;

        return true;
    }

    private function isBetweenCountSize(iterable $input): bool
    {
        $count = $input instanceof Traversable ? iterator_count($input) : count($input);

        return $this->isMoreThanMinCount($count) && $this->isLessThanMaxCount($count);
    }

    private function isMoreThanMinCount(int $count): bool
    {
        return $count >= $this->minCount;
    }

    private function isLessThanMaxCount(int $count): bool
    {
        return $this->maxCount === null || $count <= $this->maxCount;
    }
}
