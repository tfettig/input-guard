<?php
declare(strict_types=1);

namespace InValTest;

use InVal\Configuration;
use InVal\Vals\IntVal;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDefaultValueForIntVal(): void
    {
        self::assertNull((new Configuration())->defaultValue(IntVal::class));
    }
}
