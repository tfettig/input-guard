<?php
declare(strict_types=1);

namespace InVal\Vals;

class IterableStringVal implements CompleteVal
{
    use CompleteValTrait;
    use SingleInputIterableValidationTrait;
    use StringTrait {
        // Use the iterable's validation as the primary validation logic and rename the string validation method.
        SingleInputIterableValidationTrait::validation insteadof StringTrait;
        StringTrait::validation as stringValidation;
    }

    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraIterableValidation(iterable $input): bool
    {
        foreach ($input as $item) {
            $value = null;
            if ($this->stringValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}
