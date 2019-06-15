<?php
declare(strict_types=1);

namespace InputGuard\Guards;

use InputGuard\Guards\Bases\SingleInput;

class BoolGuard implements Guard
{
    use ErrorMessagesBase;
    use SingleInput;

    /**
     * @var bool
     */
    private $pseudoBools = false;

    /**
     * @param mixed     $input
     * @param bool|null $default
     */
    public function __construct($input, ?bool $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    public function pseudoBools(): self
    {
        $this->pseudoBools = true;

        return $this;
    }

    public function value(): ?bool
    {
        $this->success();

        return $this->value;
    }

    /**
     * @param mixed $input
     * @param mixed $value
     *
     * @return bool
     */
    protected function validation($input, &$value): bool
    {
        if ($this->isTrue($input)) {
            $value  = true;
            $return = true;
        } elseif ($this->isFalse($input)) {
            $value  = false;
            $return = true;
        }

        return $return ?? false;
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function isTrue($input): bool
    {
        return $input === true || $this->isPseudoTrue($input);
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function isPseudoTrue($input): bool
    {
        return $this->pseudoBools && \in_array($input, [1, '1'], true);
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function isFalse($input): bool
    {
        return $input === false || $this->isPseudoFalse($input);
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function isPseudoFalse($input): bool
    {
        return $this->pseudoBools && \in_array($input, [0, '0', ''], true);
    }
}
