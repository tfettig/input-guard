<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\IntGuard;
use PHPUnit\Framework\TestCase;

class IntGuardTest extends TestCase
{

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess(): void
    {
        $val = new IntGuard(5);
        self::assertSame(5, $val->value());
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed    $input
     * @param int|null $default
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, ?int $default, string $message): void
    {
        $val = new IntGuard($input, $default);
        self::assertSame($input, $val->value(), $message);
    }

    public function failureProvider(): array
    {
        return [
            [5, 99, 'Default as int.'],
            [5, null, 'Default as null.'],
        ];
    }
}
