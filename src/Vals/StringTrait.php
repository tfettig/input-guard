<?php
declare(strict_types=1);

namespace InVal\Vals;

trait StringTrait
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

    private function stringValidation(string $input): bool
    {
        $length = mb_strlen($input, mb_detect_encoding($input));
        if ($length < $this->minLen || ($this->maxLen !== null && $length > $this->maxLen)) {
            return false;
        }

        if ($this->regex && !preg_match($this->regex, $input)) {
            return false;
        }

        return true;
    }
}
