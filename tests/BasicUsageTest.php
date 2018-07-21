<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use InVal\Vals\CompleteVal;
use InVal\Vals\ErrorMessageTrait;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * This is a unit test to show off the basic usages of the package.
 */
class BasicUsageTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBasicUsage(): void
    {
        // Instantiate a validation object.
        $validation = new InVal();

        // An integer validation that will be successful.
        $validation->intVal(1)
                   ->errorMessage('This message will not be present on validation.');

        // An integer validation that will also be successful.
        $validation->intVal('1')
                   ->errorMessage('This message will not be present on validation.');

        // An integer validation that will not be successful.
        $validation->intVal('error')
                   ->errorMessage('Third int is invalid.')
                   ->errorMessage('Third int is invalid, and there is another error message with it.');

        // A validation that will be successful even if a null is used.
        $validation->intVal(null)
                   ->allowNull()
                   ->errorMessage('This message will not be present on validation.');

        // A validation that will be successful even if an empty string is used.
        $validation->intVal('')
                   ->allowEmptyString()
                   ->errorMessage('This message will not be present on validation.');

        // A string validation that will be successful.
        $validation->stringVal('string')
                   ->errorMessage('This message will not be present on validation.')
                   ->regex('/\w+/')
                   ->minLen(0)
                   ->maxLen(500);

        // A string validation that will be successful and return as a string
        $validation->stringVal(1)
                   ->errorMessage('This message will not be present on validation.')
                   ->betweenLen(1, null);

        $validation->stringableVal('string')
                   ->errorMessage('This message will not be present on validation.');

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

        // A validation that captures failure immediately but will still show up when everything is validated.
        $success = $validation->stringVal(null)
                              ->errorMessage('This error message will come after the others before it.')
                              ->success();

        self::assertFalse($success);

        // A user defined validation class that can be injected into the validation object.
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

        // A float validation
        $validation->floatVal(1.1)
                   ->errorMessage('This message will not be present on validation.');

        // An instanceof validation
        $validation->instanceOfVal(new stdClass(), stdClass::class)
                   ->errorMessage('This message will not be present on validation.');


        self::assertFalse($validation->success());
        self::assertSame(
            [
                'Third int is invalid.',
                'Third int is invalid, and there is another error message with it.',
                'This error message will come after the others before it.',
            ],
            $validation->pullErrorMessages()
        );
    }
}
