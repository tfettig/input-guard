<?php
declare(strict_types=1);

namespace InVal\Vals;

class IterableFloatVal implements BuildableVal
{
    use CompleteValTrait;
    use FloatTrait {
        // Use the Iterable's validation as the primary validation logic and rename the float validation method.
        SingleInputIterableValidationTrait::validation insteadof FloatTrait;
        FloatTrait::validation as floatValidation;
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
            if ($this->floatValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}
