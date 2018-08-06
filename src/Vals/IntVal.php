<?php
declare(strict_types=1);

namespace InVal\Vals;

class IntVal implements CompleteVal
{
    use CompleteValTrait;
    use IntTrait;
    use SingleInputValidationTrait;

    public function __construct($input, ?int $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

    public function value(): ?int
    {
        $this->success();

        return $this->value;
    }
}
