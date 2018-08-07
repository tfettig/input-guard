<?php
declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait StringBase
{
    /**
     * @var int
     */
    private $minLen = 0;

    /**
     * @var int|null
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
    abstract protected function extraStringValidation($input): bool;

    protected function validation($input, &$value): bool
    {
        if ($this->extraStringValidation($input) === false) {
            return false;
        }

        $inputString = (string)$input;

        $length = mb_strlen($inputString, mb_detect_encoding($inputString));
        if ($length < $this->minLen || ($this->maxLen !== null && $length > $this->maxLen)) {
            return false;
        }

        if ($this->regex && !preg_match($this->regex, $inputString)) {
            return false;
        }

        $value = is_scalar($input) ? $inputString : $input;

        return true;
    }
}
