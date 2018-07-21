<?php
declare(strict_types=1);

namespace InVal\Vals;

class StringVal implements CompleteVal
{
    use ErrorMessageTrait;
    use ValidateSingleInputTrait;

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

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

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

    protected function validation(): bool
    {
        // Short circuit for anything not a integer, float, string or boolean.
        if (\is_scalar($this->input) === false) {
            return false;
        }

        $input = (string)$this->input;
        $length = mb_strlen($input, mb_detect_encoding($input));
        if ($length < $this->minLen || ($this->maxLen !== null && $length > $this->maxLen)) {
            return false;
        }

        if ($this->regex && !preg_match($this->regex, $input)) {
            return false;
        }

        $this->value = $input;

        return true;
    }

    public function value(): ?string
    {
        $this->success();

        return $this->value;
    }
}
