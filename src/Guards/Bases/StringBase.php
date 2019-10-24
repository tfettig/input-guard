<?php

declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait StringBase
{
    use Strict;

    /**
     * @var int
     */
    private $minLen = 0;

    /**
     * @var int|\null
     */
    private $maxLen;

    /**
     * @var string
     */
    private $regex = '';

    public function minLen(int $min): self
    {
        $this->minLen = $min;

        return $this;
    }

    public function maxLen(?int $max): self
    {
        $this->maxLen = $max;

        return $this;
    }

    public function betweenLen(int $min, ?int $max): self
    {
        $this->minLen = $min;
        $this->maxLen = $max;

        return $this;
    }

    public function regex(string $regex): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * A method to allow extra validation to be done for strings.
     *
     * @param mixed $input
     *
     * @return bool
     */
    abstract protected function validationShortCircuit($input): bool;

    /**
     * @param mixed $input
     * @param mixed $value
     *
     * @return bool
     */
    protected function validation($input, &$value): bool
    {
        return $this->validationShortCircuit($input) && $this->validationComplete($input, $value);
    }

    /**
     * @param mixed $input
     * @param mixed $value
     *
     * @return bool
     */
    private function validationComplete($input, &$value): bool
    {
        $inputString = (string)$input;

        if ($this->isBetweenLength($inputString) && $this->passesRegex($inputString)) {
            $value = is_scalar($input) ? $inputString : $input;

            return true;
        }

        return false;
    }

    private function isBetweenLength(string $input): bool
    {
        $length = mb_strlen($input, mb_detect_encoding($input));

        return $this->isMoreThanMinLength($length) && $this->isLessThanMaxLength($length);
    }

    private function isMoreThanMinLength(int $length): bool
    {
        return $length >= $this->minLen;
    }

    private function isLessThanMaxLength(int $length): bool
    {
        return $this->maxLen === null || $length <= $this->maxLen;
    }

    private function passesRegex(string $input): bool
    {
        return $this->regex === '' || preg_match($this->regex, $input) === 1;
    }
}
