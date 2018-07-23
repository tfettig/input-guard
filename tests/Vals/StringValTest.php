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
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, string $message): void
    {
        self::assertSame((string)$input, (new StringVal($input))->value(), $message);
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
        $val = (new StringVal($input))->maxLen($max);

        self::assertNull($val->value(), $message);
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