<?php
declare(strict_types=1);

namespace InVal\Vals;

class FloatVal implements CompleteVal
{
    use ErrorMessageTrait;

    /**
     * @var mixed
     */
    private $input;

    /**
     * @var int|null
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

    public function __construct($input, ?float $default = null)
    {
        $this->input = $input;
        $this->value = $default;
    }

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

    public function success(): bool
    {
        if ($this->validated === null) {
            if ($this->allowNull && $this->input === null) {
                $this->validated = true;
            } elseif ($this->allowEmptyString && $this->input === '') {
                $this->validated = true;
            } else {
                $options         = [
                    'options' => [
                        'min_range' => PHP_FLOAT_MIN,
                        'max_range' => PHP_FLOAT_MAX,
                    ],
                ];
                $value           = filter_var($this->input, FILTER_VALIDATE_FLOAT, $options);
                $this->validated = $value !== false;
                $this->value     = $this->validated ? $value : $this->value;
            }
        }

        return $this->validated;
    }

    public function value(): ?float
    {
        $this->success();

        return $this->value;
    }
}
