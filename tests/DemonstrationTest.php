<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use InVal\Vals\CompleteVal;
use InVal\Vals\ErrorMessageTrait;
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
     * 2) Null values can be optional included in the validation set.
     * 3) Empty strings can be optional included in the validation set.
     * 4) Multiple error message can be set.
     * 5) When duplicate error messages are configured only one is displayed.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntegerValidationDemonstration(): void
    {
        // Instantiate a validation object.
        $validation = new InVal();

        // Success
        $validation->intVal(1)
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->intVal('1')
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
        // @todo There is a gap here for pulling error messages without calling the success method.
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBasicUsage(): void
    {
        // Instantiate a validation object.
        $validation = new InVal();

        /* String demonstration:
         * 1) Use of regex, and minimum and maximum string lengths.
         * 2) Type juggling using a non-string.
         * 3) An objects that implement __toString().
         */

        // Success
        $validation->stringVal('string')
                   ->errorMessage('This message will not be present on validation.')
                   ->regex('/\w+/')
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

        /* Boolean demonstration:
         * 1) A regular boolean input.
         * 2) A pseudo boolean input.
         */

        // Success
        $validation->boolVal(false)
                   ->errorMessage('This message will not be present on validation.');

        // Success
        $validation->boolVal('1')
                   ->allowPseudoBools()
                   ->errorMessage('This message will not be present on validation.');

        /* Float demonstration:
         * 1) A regular float input.
         */

        // Success
        $validation->floatVal(1.1)
                   ->errorMessage('This message will not be present on validation.');

        /* InstanceOf demonstration:
         * 1) An object instance input
         */

        // Success
        $validation->instanceOfVal(new stdClass(), stdClass::class)
                   ->errorMessage('This message will not be present on validation.');


        self::assertTrue($validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAdvanceUsage(): void
    {
        // Instantiate a validation object.
        $validation = new InVal();

        // Demonstrating a validation that captures failure immediately but will still show up in the final results.
        $success = $validation->stringVal(null)
                              ->errorMessage('This error message will come after the others before it.')
                              ->success();

        self::assertFalse($success);

        // Demonstrating a user defined validation class that can be injected into the validation object.
        $validation->addVal(
            new class() implements CompleteVal
            {
                use ErrorMessageTrait;

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
