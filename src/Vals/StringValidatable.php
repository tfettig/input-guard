<?php
declare(strict_types=1);

namespace InVal\Vals;

interface StringValidatable
{
    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param int $min
     * @return $this
     */
    public function minLen(int $min);

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param int|null $max
     * @return $this
     */
    public function maxLen(?int $max);

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param int      $min
     * @param int|null $max
     *
     * @return $this
     */
    public function betweenLen(int $min, ?int $max);

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    /**
     * @param string $regex
     *
     * @return $this
     */
    public function regex(string $regex);
}
