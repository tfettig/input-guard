<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\ValidateSingleInputTrait;
use PHPUnit\Framework\TestCase;

class ValidateSingleInputTraitTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAllowNulls(): void
    {
        $object = new class()
        {
            use ValidateSingleInputTrait;

            public function __construct()
            {
                $this->input = null;
            }

            public function getValidated()
            {
                return $this->validated;
            }

            protected function validation(): bool
            {
                return false;
            }

        };

        $object->allowNull();

        self::assertTrue($object->success() && $object->getValidated());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAllowEmptyString(): void
    {
        $object = new class()
        {
            use ValidateSingleInputTrait;

            public function __construct()
            {
                $this->input = '';
            }

            public function getValidated()
            {
                return $this->validated;
            }

            protected function validation(): bool
            {
                return false;
            }
        };

        $object->allowEmptyString();

        self::assertTrue($object->success() && $object->getValidated());
    }
}
