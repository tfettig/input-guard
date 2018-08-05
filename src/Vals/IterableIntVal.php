<?php
declare(strict_types=1);

namespace InVal\Vals;

class IterableIntVal implements CompleteVal
{
    use CompleteValTrait;
    use SingleInputIterableValidationTrait;
    use SingleInputIntTrait {
        SingleInputIterableValidationTrait::allowEmptyString insteadof SingleInputIntTrait;
        SingleInputIterableValidationTrait::allowNull insteadof SingleInputIntTrait;
        SingleInputIterableValidationTrait::success insteadof SingleInputIntTrait;
        SingleInputIterableValidationTrait::validation insteadof SingleInputIntTrait;
        SingleInputIntTrait::validation as intValidation;
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
            if ($this->intValidation($item, $value) === false) {
                return false;
            }
        }

        return true;
    }
}
