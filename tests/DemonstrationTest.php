<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use InVal\Vals\CompleteVal;
use InVal\Vals\CompleteValTrait;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * This is a unit test to demonstrate usages of the package.
 */
class DemonstrationTest extends TestCase
{
    /**
     * Integer demonstration:
     *
     * 1) Native type juggling for inputs will be respected.
     * 2) Adding a range to the validation.
     * 3) Null values can be optional included in the validation set.
     * 4) Empty strings can be optional included in the validation set.
     * 5) Multiple error message can be set.
     * 6) When duplicate error messages are configured only one is displayed.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntegerValidationDemonstration(): void
    {
        $validation = new InVal();

        // Success
        $validation->intVal(1)
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->intVal('1')
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->intVal(5)
                   ->min(PHP_INT_MIN)
                   ->errorMessage('This message will not be present on validation.');

        $validation->intVal(5)
                   ->between(0, 10)
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->intVal(null)
                   ->allowNull()
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->intVal('')
                   ->allowEmptyString()
                   ->errorMessage('This message will not be present on validation.');

        // Failure
        $validation->intVal('error')
                   ->errorMessage("A string of 'error' is invalid.");

        // Failure
        $validation->intVal('second error')
                   ->errorMessage("A string of 'error' is invalid.")
                   ->errorMessage("A string of 'error' is invalid, and there is another error message with it.");

        // Assertions demonstrating the value of the entire validation class and the error messages returned.
        self::assertFalse($validation->success());
        self::assertSame(
            [
                "A string of 'error' is invalid.",
                "A string of 'error' is invalid, and there is another error message with it.",
            ],
            $validation->pullErrorMessages()
        );
    }


    /**
     * Float demonstration:
     * 1) A float boolean input.
     * 2) A flat as a string.
     * 3) An integer.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatValidationDemonstration(): void
    {
        $validation = new InVal();

        // Success
        $validation->floatVal(1.1)
                   ->errorMessage('This message will not be present on validation.');

        $validation->floatVal('1.1')
                   ->between(.5, 2)
                   ->errorMessage('This message will not be present on validation.');

        $validation->floatVal(1)
                   ->min(.1)
                   ->errorMessage('This message will not be present on validation.');

        self::assertTrue($validation->success());
    }

    /**
     * String demonstration:
     * 1) Use of regex, and minimum and maximum string lengths.
     * 2) Type juggling using a non-string.
     * 3) An objects that implement __toString().
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringValidationDemonstration(): void
    {
        $validation = new InVal();

        // Success
        $validation->stringVal('A string value that needs to be validated.')
                   ->errorMessage('This message will not be present on validation.')
                   ->regex('/^[\w .]+$/')
                   ->minLen(0)
                   ->maxLen(500);

        // Success
        $validation->stringVal(1)
                   ->errorMessage('This message will not be present on validation.')
                   ->betweenLen(1, null);

        // Success
        $validation->stringableVal(
            new class()
            {
                public function __toString()
                {
                    return 'string';
                }
            }
        )
                   ->errorMessage('This message will not be present on validation.');

        self::assertTrue($validation->success());
    }

    /**
     * Boolean demonstration:
     * 1) A regular boolean input.
     * 2) A pseudo boolean input (0, '0', '', 1, '1').
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBooleanValidationDemonstration(): void
    {
        $validation = new InVal();

        // Success
        $validation->boolVal(false)
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->boolVal('1')
                   ->allowPseudoBools()
                   ->errorMessage('This message will not be present on validation.');

        self::assertTrue($validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableDemonstration(): void
    {
        $validation = new InVal();

        $validation->iterableVal([1, 'a', new stdClass()])
                   ->errorMessage('This message will not be present on validation.');

        $validation->iterableIntVal([1, 2, 3])
                   ->errorMessage('This message will not be present on validation.');

        self::assertTrue($validation->success());
    }

    /**
     * InstanceOf demonstration:
     * 1) An object instance input
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testOtherValidationDemonstration(): void
    {
        $validation = new InVal();

        // Success
        $validation->instanceOfVal(new stdClass(), stdClass::class)
                   ->errorMessage('This message will not be present on validation.');


        self::assertTrue($validation->success());
    }

    /**
     * Advance usage demonstration:
     * 1) Capture validation state immediately.
     * 2) User defined validation classes.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAdvanceUsageDemonstration(): void
    {
        $validation = new InVal();

        // Failure
        $success = $validation->stringVal(null)
                              ->errorMessage('This error message will come after the others before it.')
                              ->success();

        self::assertFalse($success);

        // Success
        $validation->addVal(
            new class() implements CompleteVal
            {
                use CompleteValTrait;

                public function success(): bool
                {
                    return true;
                }

                public function value()
                {
                    return 'A custom validation object.';
                }

            }
        )
                   ->errorMessage('This message will not be present on validation.');

        self::assertFalse($validation->success());
        self::assertSame(
            [
                'This error message will come after the others before it.',
            ],
            $validation->pullErrorMessages()
        );
    }
}
