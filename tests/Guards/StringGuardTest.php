<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\StringGuard;
use PHPUnit\Framework\TestCase;
use stdClass;

class StringGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param          $input
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, string $message): void
    {
        self::assertSame((string)$input, (new StringGuard($input))->value(), $message);
    }

    /**
     * @return array
     */
    public function successProvider(): array
    {
        return [
            ['', 'Empty string scalar'],
            ['success', 'String scalar'],
            [1, 'Integer scalar'],
            [1.1, 'Float scalar'],
            [false, 'Boolean scalar'],
        ];
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure(): void
    {
        self::assertNull((new StringGuard(new stdClass()))->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStrictFailure(): void
    {
        $guard = (new StringGuard(1))->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertNull($guard->value());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStrictSuccess(): void
    {
        $guard = (new StringGuard('1'))->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertSame('1', $guard->value());
    }
}
