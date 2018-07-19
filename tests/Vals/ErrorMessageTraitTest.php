<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\CompleteVal;
use InVal\Vals\ErrorMessageTrait;
use PHPUnit\Framework\TestCase;

class ErrorMessageTraitTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPullErrorMessages(): void
    {
        $class = new class() implements CompleteVal
        {
            use ErrorMessageTrait;

            public function success(): bool
            {
                return true;
            }

            public function value()
            {
                return null;
            }

        };

        $class->errorMessage('first');
        $class->errorMessage('second');

        $errors = $class->pullErrorMessages();

        self::assertSame('first', array_shift($errors));
        self::assertSame('second', array_shift($errors));
    }
}
