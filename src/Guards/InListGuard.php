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
     * InListVal constructor.
     *
     * @param mixed      $input
     * @param iterable   $list
     * @param mixed|null $defaultValue
     * @param bool       $defaultStrict
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

    private function validateCastableList($input): bool
    {
        return \in_array($input, (array)$this->list, $this->strict);
    }

    private function validateTraversableList($input): bool
    {
        $validate = $this->strict
            ? static function ($value) use ($input): bool {
                return $value === $input;
            }
            : static function ($value) use ($input): bool {
                /** @noinspection TypeUnsafeComparisonInspection */
                return $value == $input;
            };

        foreach ($this->list as $item) {
            if ($validate($item)) {
                return true;
            }
        }

        return false;
    }
}
