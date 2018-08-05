<?php
declare(strict_types=1);

namespace InVal\Vals;

class IterableVal implements CompleteVal
{
    use CompleteValTrait;
    use SingleInputIterableValidationTrait;

    public function __construct($input, ?iterable $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    protected function extraIterableValidation(): bool
    {
        return true;
    }
}
