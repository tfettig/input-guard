<?php
declare(strict_types=1);

namespace InVal\Vals;

trait ValidateSingleInputTrait
{
    /**
     * The input to be validated.
     *
     * @var mixed
     */
    private $input;

    /**
     * The value to be returned after validation is complete.
     *
     * @var mixed
     */
    private $value;

    /**
     * A flag that indicates the validated state the object is in.
     *
     * @var bool|null
     */
    private $validated;

    /**
     * A flag that if set to true indicates that a null as the input is acceptable.
     *
     * @var bool
     */
    private $allowNull = false;

    /**
     * A flag that if set to true indicates that an empty string as the input is acceptable.
     *
     * @var bool
     */
    private $allowEmptyString = false;

    /**
     * The validation algorithm that determines if the input was successfully validated.
     *
     * If PHP allowed the behavior the method would be private.
     *
     * @return bool
     */
    abstract protected function validation(): bool;

    /**
     * A switch to update the object state to allow nulls as a valid value for the input.
     *
     * @return $this
     */
    public function allowNull(): self
    {
        $this->allowNull = true;

        return $this;
    }

    /**
     * A switch to update the object state to allow empty strings as a valid value for the input.
     *
     * @return $this
     */
    public function allowEmptyString(): self
    {
        $this->allowEmptyString = true;

        return $this;
    }

    /**
     * Execute to determine the success status of the input validation.
     *
     * @return bool
     */
    public function success(): bool
    {
        if ($this->validated === null) {
            if ($this->allowNull && $this->input === null) {
                $this->validated = true;
            } elseif ($this->allowEmptyString && $this->input === '') {
                $this->validated = true;
            } else {
                $this->validated = $this->validation();
            }
        }

        return $this->validated;
    }
}
