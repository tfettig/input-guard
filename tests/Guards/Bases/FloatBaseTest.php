<?php
declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use Exception;
use InputGuard\Guards\Bases\FloatBase;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class FloatBaseTest extends TestCase
{
    /**
     * @var FloatBase
     */
    private $guard;

    public function setUp(): void
    {
        parent::setUp();

        $this->guard = new class()
        {
            use FloatBase;

            protected function extraStringValidation($input): bool
            {
                /** @noinspection SuspiciousBinaryOperationInspection */
                return $input === $input;
            }

            public function runValidation($input): bool
            {
                $value = null;
                return $this->validation($input, $value);
            }
        };
    }

    /**
     * @dataProvider successProvider
     *
     * @param mixed  $input
     * @param float  $min
     * @param float  $max
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, float $min, float $max, string $message): void
    {
        $this->guard->min($min);
        $this->guard->max($max);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertTrue($this->guard->runValidation($input), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function successProvider(): array
    {
        return [
            ['55', 1, 100, 'As a string'],
            ['55.55', 1, 100, 'As a string float'],
            [1.5, 0, 2.5, 'Using between'],
            [1, 1, 2, 'Input and min are equal'],
            [2, 1, 2, 'Input and max are equal'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBetweenSuccess(): void
    {
        $this->guard->min(1.5)
                    ->max(9.9);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertTrue($this->guard->runValidation(3.3333));
    }


    /**
     * @dataProvider failureProvider
     *
     * @param mixed  $input
     * @param float  $min
     * @param float  $max
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, float $min, float $max, string $message): void
    {
        $this->guard->min($min)
                    ->max($max);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertFalse($this->guard->runValidation($input), $message);
    }

    public function failureProvider(): array
    {
        return [
            ['one.point.one', 1, 15, 'Input as string'],
            [true, 0, 2.5, 'Input as boolean'],
            [new stdClass(), 0, 2.5, 'Input as object'],
            [0, 1, 2, 'Input less then min'],
            [3, 1, 2, 'Input greater than max'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testStrictFailure(): void
    {
        $this->guard->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertFalse($this->guard->runValidation('5.5'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testStrictSuccess(): void
    {
        $this->guard->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertTrue($this->guard->runValidation(5.5));
    }
}
