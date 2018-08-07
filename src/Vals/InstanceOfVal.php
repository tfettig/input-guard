<?php
declare(strict_types=1);

namespace InVal\Vals;

class InstanceOfVal implements BuildableVal
{
    use CompleteValTrait;
    use SingleInputValidationTrait;

    /**
     * @var string
     */
    private $className;

    public function __construct($input, string $className)
    {
        $this->className = $className;
        $this->input     = $input;
    }

    protected function validation($input, &$value): bool
    {
        if ($input instanceof $this->className) {
            $value = $input;
            return true;
        }

        return false;
    }

    public function value(): ?object
    {
        $this->success();

        return $this->value;
    }
}
