<?php

declare(strict_types=1);

namespace InputGuard\Guards;

use ArrayObject;
use InputGuard\Guards\Bases\SingleInput;
use InputGuard\Guards\Bases\Strict;

class InListGuard implements Guard
{
    use ErrorMessagesBase;
    use SingleInput;
    use Strict;

    /**
     * @var iterable
     */
    private $list;

    /**
     * @param mixed       $input
     * @param iterable    $list
     * @param mixed|\null $defaultValue
     * @param bool        $defaultStrict
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct($input, iterable $list, $defaultValue = null, bool $defaultStrict = true)
    {
        $this->input  = $input;
        $this->list   = $list;
        $this->value  = $defaultValue;
        $this->strict = $defaultStrict;
    }

    public function value()
    {
        $this->success();

        return $this->value;
    }

    protected function validation($input, &$value): bool
    {
        if ($this->isCastable() ? $this->validateCastableList($input) : $this->validateTraversableList($input)) {
            $value = $input;
            return true;
        }

        return false;
    }

    private function isCastable(): bool
    {
        return \is_array($this->list) || $this->list instanceof ArrayObject;
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function validateCastableList($input): bool
    {
        return \in_array($input, (array)$this->list, $this->strict);
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function validateTraversableList($input): bool
    {
        foreach ($this->list as $item) {
            if ($this->strict) {
                if ($item === $input) {
                    return true;
                }
                continue;
            }

            /** @noinspection TypeUnsafeComparisonInspection */
            if ($item == $input) {
                return true;
            }
        }

        return false;
    }
}
