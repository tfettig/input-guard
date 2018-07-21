<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\StringVal;
use PHPUnit\Framework\TestCase;
use stdClass;

class StringValTest extends TestCase
{
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, int $min, ?int $max, string $regex, string $message): void
    {
        $val = (new StringVal($input))
            ->minLen($min)
            ->maxLen($max)
            ->regex($regex);

        self::assertTrue($val->success(), $message);
        self::assertSame((string)$input, $val->value(), $message);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBetweenSuccess(): void
    {
        $val = (new StringVal('string'))->betweenLen(0, 10);

        self::assertTrue($val->success());
        self::assertSame('string', $val->value());
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed    $input
     * @param int      $min
     * @param int|null $max
     * @param string   $regex
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, int $min, ?int $max, string $regex, string $message): void
    {
        $val = (new StringVal($input))
            ->minLen($min)
            ->maxLen($max)
            ->regex($regex);

        self::assertFalse($val->success(), $message);
        self::assertNull($val->value(), $message);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            ['The birds', 0, 9, '/[\w]+/', 'English string'],
            ['Die Vögel', 0, null, '/[\w]+/', 'German string'],
            ['鳥たち', 0, 3, '/[\w]+/u', 'Japanese string'],
            [1, 0, 9, '/[\w]+/', 'An integer that can be safely converted to a string'],
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function failureProvider(): array
    {
        return [
            ['failure', 0, 1, '/[\w]+/', 'Too long'],
            ['failure', 15, null, '/[\w]+/', 'Too short'],
            ['failure', 0, null, '/[\d]+/', 'Non-matching pattern'],
            [new stdClass(), 0, null, '/[\w]+/', 'A non-scalar'],
        ];
    }
}
