<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use InVal\Vals\CompleteVal;
use InVal\Vals\ErrorMessageTrait;
use PHPUnit\Framework\TestCase;
use stdClass;

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
                   ->errorMessage('First int is invalid.');

        // An integer validation that will not be successful.
        $validation->intVal('error')
                   ->errorMessage('Second int is invalid.')
                   ->errorMessage('Second int is invalid with more then one error message.');

        // A float validation that will be successful.
        $validation->floatVal(1.1)
                   ->errorMessage('First float is invalid.');

        // A float validation that will not be successful.
        $validation->floatVal('error')
                   ->errorMessage('Second float is invalid.');

        // A instanceof validation that will be successful.
        $validation->instanceOfVal(new stdClass(), stdClass::class)
                   ->errorMessage('First instanceof is invalid.');

        // A instanceof validation that will not be successful.
        $validation->instanceOfVal('error', stdClass::class)
                   ->errorMessage('Second instanceof is invalid.');

        // A user defined validation class created by implementing the required interface.
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
                   ->errorMessage('error');


        self::assertFalse($validation->success());
        self::assertSame(
            [
                'Second int is invalid.',
                'Second int is invalid with more then one error message.',
                'Second float is invalid.',
                'Second instanceof is invalid.',
            ],
            $validation->pullErrorMessages()
        );
    }
}
