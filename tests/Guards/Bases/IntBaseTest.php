<?php
declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use Exception;
use InputGuard\Guards\Bases\IntBase;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class IntBaseTest extends TestCase
{
    /**
     * @var IntBase
     */
    private $guard;

    public function setUp(): void
    {
        parent::setUp();

        $this->guard = new class()
        {
            use IntBase;

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
     * @param            $input
     *
     * @param int|null   $min
     * @param int|null   $max
     * @param string     $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, ?int $min, ?int $max, string $message): void
    {
        $this->guard->min($min)
                    ->max($max);

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
            [5, 1, 10, 'Right in the middle.'],
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
        $this->guard->min(500)
                    ->max(5000);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertTrue($this->guard->runValidation(1000));
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed    $input
     * @param int|null $min
     * @param int|null $max
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, ?int $min, ?int $max, string $message): void
    {
        $this->guard->min($min)
                    ->max($max);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertFalse($this->guard->runValidation($input), $message);
    }

    public function failureProvider(): array
    {
        return [
            ['one.point.one', 0, 1, 'Input as string'],
            [1.5, 0, 2, 'Input as float.'],
            [true, 0, 2, 'Input as boolean'],
            ['', 0, 2, 'Input as empty string'],
            [new stdClass(), 0, 2, 'Input as object'],
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
        self::assertFalse($this->guard->runValidation('5'));
    }
}
