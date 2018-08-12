<?php
declare(strict_types=1);

namespace InputGuardTests;

use ArrayObject;
use InputGuard\InputGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use stdClass;

class InputGuardTest extends TestCase
{
    /**
     * @var InputGuard
     */
    private $validation;

    public function setUp(): void
    {
        $this->validation = new InputGuard();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAutoload(): void
    {
        self::assertInstanceOf(InputGuard::class, $this->validation);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testValue(): void
    {
        self::assertSame($this->validation, $this->validation->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testValidationOccursWhenValueIsCalled(): void
    {
        $this->validation->int('Invalid input');

        self::assertFalse($this->validation->value()->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ReflectionException
     */
    public function testClone(): void
    {
        // Get the values of all properties of a freshly created InputGuard.
        $reflect       = new ReflectionClass($this->validation);
        $defaultValues = array_map(function (ReflectionProperty $property) {
            $property->setAccessible(true);
            return $property->getValue($this->validation);
        }, $reflect->getProperties());

        // Add a guard and call the methods defined in the GuardChain interface.
        $this->validation->int(1);
        $this->validation->success();
        $this->validation->errorMessage('An error.');
        $this->validation->pullErrorMessages();
        $this->validation->value();

        // Clone the updated InputGuard object and then get all the property values.
        $newValidation = clone $this->validation;
        $reflect       = new ReflectionClass($newValidation);
        $newValues     = array_map(function (ReflectionProperty $property) use ($newValidation) {
            $property->setAccessible(true);
            return $property->getValue($newValidation);
        }, $reflect->getProperties());

        // Assert that the values of a brand new InputGuard matches that of a cloned InputGuard that was used.
        self::assertSame($defaultValues, $newValues);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccessSuccess(): void
    {
        self::assertTrue($this->validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccessFailure(): void
    {
        $this->validation->int('fail');
        self::assertFalse($this->validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCallingSuccessMultipleTimesDoesNotChangeState(): void
    {
        self::assertSame($this->validation->success(), $this->validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCallingSuccessMultipleTimesAfterUpdatesDoesChangeState(): void
    {
        self::assertTrue($this->validation->success());
        $this->validation->int('fail');
        self::assertFalse($this->validation->success());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testErrorMessage(): void
    {
        $error = 'error';
        $this->validation->errorMessage($error);
        self::assertSame($this->validation->pullErrorMessages(), [$error]);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRemovingDuplicatedErrorMessages(): void
    {
        $this->validation->int('Incorrect input')
                         ->errorMessage('The same message');

        $this->validation->float('Incorrect input')
                         ->errorMessage('The same message')
                         ->errorMessage('The same message');

        $this->validation->errorMessage('The same message');

        $this->validation->success();

        self::assertCount(1, $this->validation->pullErrorMessages());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBoolVal(): void
    {
        $input = false;
        self::assertSame($input, $this->validation->bool($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntVal(): void
    {
        $input = 1;
        self::assertSame($input, $this->validation->int($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatVal(): void
    {
        $input = 1.1;
        self::assertSame($input, $this->validation->float($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInstanceOfVal(): void
    {
        $input = new stdClass();
        self::assertSame($input, $this->validation->instanceOf($input, stdClass::class)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringVal(): void
    {
        $input = 'string';
        self::assertSame($input, $this->validation->string($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStringableVal(): void
    {
        $input = new class()
        {
            public function __toString()
            {
                return 'string';
            }
        };

        self::assertSame($input, $this->validation->stringable($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testArrayVal(): void
    {
        $input = [0, 1, 2];
        self::assertSame($input, $this->validation->array($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableVal(): void
    {
        $input = new ArrayObject();
        self::assertSame($input, $this->validation->iterable($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableIntVal(): void
    {
        $input = [1, 2, 3];
        self::assertSame($input, $this->validation->iterableInt($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableFloatVal(): void
    {
        $input = [1.1, 2.9, 3];
        self::assertSame(array_map('\floatval', $input), $this->validation->iterableFloat($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableStringVal(): void
    {
        $input = ['one', 'two', 'three'];
        self::assertSame($input, $this->validation->iterableString($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterableStringableVal(): void
    {
        $input = [
            new class()
            {
                public function __toString()
                {
                    return 'success';
                }
            },
        ];
        self::assertSame($input, $this->validation->iterableStringable($input)->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInListVal(): void
    {
        $input = 0;
        self::assertSame($input, $this->validation->inList($input, [0])->value());
        self::assertSame(1, $this->guardCount());
    }

    /**
     * @return int
     * @throws ExpectationFailedException
     */
    private function guardCount(): int
    {
        try {
            $reflect = new ReflectionClass($this->validation);
        } catch (ReflectionException $e) {
            throw new ExpectationFailedException('Could not get Guard count', null, $e);
        }

        $property = $reflect->getProperty('guards');
        $property->setAccessible(true);
        return count($property->getValue($this->validation));
    }
}
