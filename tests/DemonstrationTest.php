<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

namespace InputGuardTests;

use InputGuard\InputGuard;
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
        $validation = new InputGuard();

        $validation->int(1)
                   ->errorMessage('This message will not be present on validation.');

        // A string that PHP can cast to an integer will be considered valid in non-strict mode.
        $validation->int('1')
                   ->errorMessage('This message will not be present on validation.');

        $validation->int(5)
                   ->min(PHP_INT_MIN)
                   ->errorMessage('This message will not be present on validation.');

        $validation->int(5)
                   ->between(0, 10)
                   ->errorMessage('This message will not be present on validation.');

        // Nulls can be optionally allowed.
        $validation->int(null)
                   ->allowNull()
                   ->errorMessage('This message will not be present on validation.');

        // Empty strings can be optionally allowed.
        $validation->int('')
                   ->allowEmptyString()
                   ->errorMessage('This message will not be present on validation.');

        self::assertTrue($validation->success());
        self::assertSame([], $validation->pullErrorMessages());
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
        $validation = new InputGuard();

        $validation->float(1.1)
                   ->errorMessage('This message will not be present on validation.');

        $validation->float('1.1')
                   ->between(.5, 2)
                   ->errorMessage('This message will not be present on validation.');

        $validation->float(1)
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
        $validation = new InputGuard();

        $validation->string('A string value that needs to be validated.')
                   ->errorMessage('This message will not be present on validation.')
                   ->regex('/^[\w .]+$/')
                   ->minLen(0)
                   ->maxLen(500);

        $validation->string(1)
                   ->errorMessage('This message will not be present on validation.')
                   ->betweenLen(1, null);

        $validation->stringable(
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
        $validation = new InputGuard();

        $validation->bool(false)
                   ->errorMessage('This message will not be present on validation.');

        $validation->bool('1')
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
        $validation = new InputGuard();

        $validation->iterable([1, 'a', new stdClass()])
                   ->errorMessage('This message will not be present on validation.');

        $validation->iterableInt([1, 2, 3])
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
    public function testInstanceOfDemonstration(): void
    {
        $validation = new InputGuard();

        $validation->instanceOf(new stdClass(), stdClass::class)
                   ->errorMessage('This message will not be present on validation.');

        self::assertTrue($validation->success());
    }

    /**
     * Advance usage demonstration:
     * 1) Multiple error messages can be set.
     * 2) Duplicate error messages will only be returned once when pulled.
     * 3) Guard objects can be assigned a handler and manipulated away from the InputGuard.
     * 4) Custom guard objects can be added to the InputGuard.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAdvanceUsageDemonstration(): void
    {
        $validation = new InputGuard();

        $validation->string(null)
                   ->errorMessage('This is the first error message.')
                   ->errorMessage('This is a duplicated second error message that is not shown twice.')
                   ->errorMessage('This is a duplicated second error message that is not shown twice.');

        // This assigns the instantiated StringGuard class to a local variable for later use.
        $stringGuard = $validation->string('Input that is too short.')
                                  ->errorMessage('This error message will be in the order it was added.');

        $stringGuard->minLen(100);

        // Check the success of the validation for the individual guard class.
        self::assertFalse($stringGuard->success());

        // Get the value of the StringGuard class.
        self::assertNull($stringGuard->value());

        // Custom Guard classes can be created as long as they implement the Guard interface.
        // This allows for those more complex validations to done.
        $validation->add(
            new class() implements \InputGuard\Guards\Guard
            {
                use \InputGuard\Guards\ErrorMessagesBase;

                public function success(): bool
                {
                    return false;
                }

                public function value(): string
                {
                    return 'A custom validation object.';
                }

            }
        )
                   ->errorMessage('The custom Guard failed validation.');

        self::assertFalse($validation->success());
        self::assertSame(
            [
                'This is the first error message.',
                'This is a duplicated second error message that is not shown twice.',
                'This error message will be in the order it was added.',
                'The custom Guard failed validation.',
            ],
            $validation->pullErrorMessages()
        );
    }
}
