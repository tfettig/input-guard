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
            use ValidateSingleInputTrait {
                // A hack for some weirdness in phpmd.
                ValidateSingleInputTrait::allowNull as allowNullWhat;
                ValidateSingleInputTrait::success as successWhat;
            }

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

        $object->allowNullWhat();

        self::assertTrue($object->successWhat() && $object->getValidated());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAllowEmptyString(): void
    {
        $object = new class()
        {
            use ValidateSingleInputTrait {
                // A hack for some weirdness in phpmd.
                ValidateSingleInputTrait::allowEmptyString as allowEmptyStringWhen;
                ValidateSingleInputTrait::success as successWhen;
            }

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

        $object->allowEmptyStringWhen();

        self::assertTrue($object->successWhen() && $object->getValidated());
    }
}
