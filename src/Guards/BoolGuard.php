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

    private function isTrue($input): bool
    {
        return $input === true || $this->isPseudoTrue($input);
    }

    private function isPseudoTrue($input): bool
    {
        return $this->pseudoBools && \in_array($input, [1, '1'], true);
    }

    private function isFalse($input): bool
    {
        return $input === false || $this->isPseudoFalse($input);
    }

    private function isPseudoFalse($input): bool
    {
        return $this->pseudoBools && \in_array($input, [0, '0', ''], true);
    }
}
