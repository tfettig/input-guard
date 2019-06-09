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

            public function runValidation($input): bool
            {
                $value = null;
                return $this->validation($input, $value);
            }
        };
    }

    /**
     * @dataProvider inputProvider
     *
     * @param mixed  $input
     * @param int|   $min
     * @param int|   $max
     * @param bool   $strict
     * @param bool   $expect
     * @param string $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInput($input, int $min, int $max, bool $strict, bool $expect, string $message): void
    {
        $this->guard->min($min)
                    ->max($max);

        if ($strict) {
            $this->guard->strict();
        }

        self::assertSame($expect, $this->guard->runValidation($input), $message);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function inputProvider(): array
    {
        return [
            [5, 1, 10, true, true, 'Success: (Strict) Input is in the middle.'],
            [1, 1, 2, true, true, 'Success: (Strict) Input and min are equal'],
            [2, 1, 2, true, true, 'Success: (Strict) Input and max are equal'],
            ['5', 1, 10, false, true, 'Success: Input is in the middle.'],
            ['1', 1, 2, false, true, 'Success: Input and min are equal'],
            ['2', 1, 2, false, true, 'Success: Input and max are equal'],
            ['5', 1, 10, true, false, 'Failure: (Strict) Input is in the middle.'],
            ['one.point.one', 0, 1, false, false, 'Failure: Input as string'],
            [1.5, 0, 2, false, false, 'Failure: Input as float'],
            [true, 0, 2, false, false, 'Failure: Input as boolean'],
            ['', 0, 2, false, false, 'Failure: Input as empty string'],
            [new stdClass(), 0, 2, false, false, 'Failure: Input as object'],
            [0, 1, 2, false, false, 'Failure: Input less then min'],
            [3, 1, 2, false, false, 'Failure: Input greater than max'],
        ];
    }
}
