<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\SingleInputValidationTrait;
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
            use SingleInputValidationTrait {
                // A hack for some weirdness in phpmd.
                SingleInputValidationTrait::allowNull as allowNullWhat;
                SingleInputValidationTrait::success as successWhat;
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
            use SingleInputValidationTrait {
                // A hack for some weirdness in phpmd.
                SingleInputValidationTrait::allowEmptyString as allowEmptyStringWhen;
                SingleInputValidationTrait::success as successWhen;
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
