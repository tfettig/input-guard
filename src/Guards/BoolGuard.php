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
        if ($input === true || ($this->pseudoBools && \in_array($input, [1, '1'], true))) {
            $value = true;
            return true;
        }

        if ($input === false || ($this->pseudoBools && \in_array($input, [0, '0', ''], true))) {
            $value = false;
            return true;
        }

        return false;
    }
}
