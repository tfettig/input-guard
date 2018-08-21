<?php
declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use InputGuard\Guards\Bases\Strict;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    /**
     * @var Strict
     */
    private $object;

    public function setUp()
    {
        $this->object = new class()
        {
            use Strict;

            public function getStrict(): bool
            {
                return $this->strict;
            }
        };
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testStrict(): void
    {
        $sameObject = $this->object->strict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertTrue($this->object->getStrict());
        self::assertSame($this->object, $sameObject);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNonStrict(): void
    {
        $sameObject = $this->object->nonStrict();

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertFalse($this->object->getStrict());
        self::assertSame($this->object, $sameObject);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPropertySetToFalseByDefault(): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        self::assertFalse($this->object->getStrict());
    }
}
