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

    /**
     * @param iterable $input
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function extraIterableValidation(iterable $input): bool
    {
        return true;
    }
}
