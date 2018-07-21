<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\StringableVal;
use PHPUnit\Framework\TestCase;
use stdClass;

class StringableValTest extends TestCase
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
        self::assertSame((string)$input, (new StringableVal($input))->value(), $message);
    }

    /**
     * @return array
     * @throws \Exception
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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

        $val = new StringableVal($object);

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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, ?int $max, string $message): void
    {
        $val = (new StringableVal($input))->maxLen($max);

        self::assertNull($val->value(), $message);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function failureProvider(): array
    {
        return [
            ['failure', 0, 'Scalar that fails validation.'],
            [new stdClass(), 0, 'A non-scalar'],
        ];
    }
}
