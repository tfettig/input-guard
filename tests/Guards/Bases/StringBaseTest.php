<?php

declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use InputGuard\Guards\Bases\StringBase;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class StringBaseTest extends TestCase
{
    /**
     * @var StringBase
     */
    private $guard;

    public function setUp(): void
    {
        parent::setUp();

        $this->guard = new class () {
            use StringBase;

            protected function validationShortCircuit(/** @noinspection PhpUnusedParameterInspection */ $input): bool
            {
                return true;
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
     * @param          $input
     *
     * @param int      $min
     * @param int|null $max
     * @param string   $regex
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, int $min, ?int $max, string $regex, string $message): void
    {
        $this->guard->minLen($min)
                    ->maxLen($max)
                    ->regex($regex);

        self::assertTrue($this->guard->runValidation($input), $message);
    }

    /**
     * @return array
     */
    public function successProvider(): array
    {
        return [
            ['', 0, 9, '/[\w]*/', 'Empty string'],
            ['The birds', 0, 9, '/[\w]+/', 'English string'],
            ['Die Vögel', 0, null, '/[\w]+/', 'German string'],
            ['鳥たち', 0, 3, '/[\w]+/u', 'Japanese string'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBetweenSuccess(): void
    {
        $this->guard->betweenLen(5, 10);

        self::assertTrue($this->guard->runValidation('success'));
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed     $input
     * @param int       $min
     * @param int|null $max
     * @param string    $regex
     * @param string    $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, int $min, ?int $max, string $regex, string $message): void
    {
        $this->guard->minLen($min)
                    ->maxLen($max)
                    ->regex($regex);

        self::assertFalse($this->guard->runValidation($input), $message);
    }

    /**
     * @return array
     */
    public function failureProvider(): array
    {
        return [
            ['failure', 0, 1, '/[\w]+/', 'Too long'],
            ['failure', 15, null, '/[\w]+/', 'Too short'],
            ['failure', 0, null, '/[\d]+/', 'Non-matching pattern'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testStrictSuccess(): void
    {
        $this->guard->strict();

        self::assertTrue($this->guard->runValidation('success'));
    }
}
