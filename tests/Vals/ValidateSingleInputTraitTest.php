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
        };

        $object->allowNull();
        $object->validate(function () {
            return false;
        });

        self::assertTrue($object->getValidated());
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
        };

        $object->allowEmptyString();
        $object->validate(function () {
            return false;
        });

        self::assertTrue($object->getValidated());
    }
}
