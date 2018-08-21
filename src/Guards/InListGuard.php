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
        // If the input is an array or an ArrayObject then it's cheap to cast it and use in_array().
        if (\is_array($this->list) || $this->list instanceof ArrayObject) {
            if (\in_array($input, (array)$this->list, $this->strict)) {
                $value = $input;
                return true;
            }

            return false;
        }

        $validate = $this->strict
            ? function ($value) use ($input): bool {
                return $value === $input;
            }
            : function ($value) use ($input): bool {
                /** @noinspection TypeUnsafeComparisonInspection */
                return $value == $input;
            };

        // Since the type hint is 'iterable' (aka Traversable) iterate over the object.
        foreach ($this->list as $item) {
            if ($validate($item)) {
                $value = $input;
                return true;
            }
        }

        return false;
    }
}
