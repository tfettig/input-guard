<?php
declare(strict_types=1);

namespace InputGuard\Guards\Bases;

trait SingleInput
{
    /**
     * The original input to be validated.
     *
     * @var mixed
     */
    private $input;

    /**
     * After validation either the original value or the default value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Indicates if the input has been validated and the result of the validation.
     *
     * @var bool|\null
     */
    private $validated;

    /**
     * Configuration for null to be valid input.
     *
     * @var bool
     */
    private $nullable = false;

    /**
     * Configuration for empty strings to be valid input.
     *
     * @var bool
     */
    private $emptyString = false;

    /**
     * Contains the input validation code.
     *
     * @param mixed  $input
     * @param mixed &$value
     *
     * @return bool
     */
    abstract protected function validation($input, &$value): bool;

    /**
     * Allow null as a valid value.
     *
     * @return $this
     */
    public function nullable(): self
    {
        $this->nullable = true;

        return $this;
    }

    /**
     * Allow empty strings as a valid value.
     *
     * @return $this
     */
    public function emptyString(): self
    {
        $this->emptyString = true;

        return $this;
    }

    /**
     * Determine the success status of the input validation.
     *
     * @return bool
     */
    public function success(): bool
    {
        if ($this->validated === null) {
            if ($this->nullable && $this->input === null) {
                $this->validated = true;
            } elseif ($this->emptyString && $this->input === '') {
                $this->validated = true;
            } else {
                $this->validated = $this->validation($this->input, $this->value);
            }
        }

        return $this->validated;
    }
}
