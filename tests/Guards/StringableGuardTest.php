<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use Exception;
use InputGuard\Guards\StringableGuard;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;

class StringableGuardTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param          $input
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSuccess($input, string $message): void
    {
        self::assertSame((string)$input, (new StringableGuard($input))->value(), $message);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function successProvider(): array
    {
        return [
            ['success', 'String scalar'],
            [1, 'Integer scalar'],
            [1.1, 'Float scalar'],
            [false, 'Boolean scalar'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testStringableObject(): void
    {
        $object = new class()
        {
            public function __toString()
            {
                return 'success';
            }
        };

        $val = new StringableGuard($object);

        self::assertSame($object, $val->value());
        self::assertSame('success', (string)$val->value());
    }


    /**
     * @dataProvider failureProvider
     *
     * @param mixed    $input
     * @param int|null $max
     * @param string   $message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFailure($input, ?int $max, string $message): void
    {
        $val = (new StringableGuard($input))->maxLen($max);

        self::assertNull($val->value(), $message);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function failureProvider(): array
    {
        return [
            ['failure', 0, 'Scalar that fails validation.'],
            [new stdClass(), 0, 'A non-scalar'],
        ];
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testStrictFailure(): void
    {
        $guard = (new StringableGuard(1))->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertNull($guard->value());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testStrictSuccess(): void
    {
        $guard = (new StringableGuard('1'))->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertSame('1', $guard->value());
    }
}
