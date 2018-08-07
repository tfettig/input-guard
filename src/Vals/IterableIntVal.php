<?php
declare(strict_types=1);

namespace InVal\Vals;

class IterableIntVal implements BuildableVal
{
    use BuildableValTrait;
    use IntTrait {
        // Use the Iterable's validation as the primary validation logic and rename the Int validation method.
        SingleInputIterableValidationTrait::validation insteadof IntTrait;
        IntTrait::validation as intValidation;
    }
    use SingleInputIterableValidationTrait;

    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraIterableValidation(iterable $input): bool
    {
        foreach ($input as $item) {
            $value = null;
            if ($this->intValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}
