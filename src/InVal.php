<?php
declare(strict_types=1);

namespace InVal;

use InVal\Vals\IntVal;

class InVal
{
    /**
     * An array of objects that implement the Valadatable interface.
     *
     * @var array
     */
    private $vals = [];

    /**
     * @var Configurable
     */
    private $configuration;

    public function __construct(Configurable $configuration)
    {
        $this->configuration = $configuration ?? new Configuration();
    }

    public function valInt($input): IntVal
    {
        $val          = new IntVal($input, $this->configuration);
        $this->vals[] = $val;

        return $val;
    }
}
