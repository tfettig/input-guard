<?php
declare(strict_types=1);

namespace InVal\Vals;

class InstanceOfVal implements Valadatable
{
    /**
     * @var mixed
     */
    private $input;

    /**
     * @var object|null
     */
    private $value;

    /**
     * @var bool|null
     */
    private $validated;

    /**
     * @var string
     */
    private $className;

    public function __construct($input, string $className)
    {
        $this->className = $className;
        $this->input     = $input;
    }

    public function success(): bool
    {
        if ($this->validated === null) {
            $this->validated = $this->input instanceof $this->className;
            $this->value     = $this->validated ? $this->input : $this->value;
        }

        return $this->validated;
    }

    public function value(): ?object
    {
        $this->success();

        return $this->value;
    }
}
