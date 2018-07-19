<?php
declare(strict_types=1);

namespace InVal\Vals;

trait ValidateSingleInputTrait
{
    /**
     * @var mixed
     */
    private $input;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool|null
     */
    private $validated;

    /**
     * @var bool
     */
    private $allowNull = false;

    /**
     * @var bool
     */
    private $allowEmptyString = false;

    public function allowNull(): self
    {
        $this->allowNull = true;

        return $this;
    }

    public function allowEmptyString(): self
    {
        $this->allowEmptyString = true;

        return $this;
    }

    /**
     * @param callable $validation The callable must return a bool to avoid unexpected behavior.
     *
     * @return bool
     */
    public function validate(callable $validation): bool
    {
        if ($this->validated === null) {
            if ($this->allowNull && $this->input === null) {
                $this->validated = true;
            } elseif ($this->allowEmptyString && $this->input === '') {
                $this->validated = true;
            } else {
                $this->validated = (bool)$validation();
            }
        }

        return $this->validated;
    }
}
