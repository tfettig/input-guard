<?php
declare(strict_types=1);

namespace InputGuardTests\Guards;

use InputGuard\Guards\ErrorMessagesBase;
use PHPUnit\Framework\TestCase;

class ErrorMessagesBaseTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPullErrorMessages(): void
    {
        $anonClass = new class()
        {
            use ErrorMessagesBase;

            public function success(): bool
            {
                return false;
            }

            public function value(): void
            {
            }
        };

        $anonClass->errorMessage('first');
        $anonClass->errorMessage('second');

        $errors = $anonClass->pullErrorMessages();

        self::assertSame('first', array_shift($errors));
        self::assertSame('second', array_shift($errors));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testThatPullingErrorMessagesUpdatesSuccessState(): void
    {
        $anonClass = new class()
        {
            use ErrorMessagesBase {
                // A hack for some weirdness in phpmd.
                ErrorMessagesBase::errorMessage as errorMessageA;
                ErrorMessagesBase::pullErrorMessages as pullErrorMessagesA;
            }

            public $success = true;

            public function success(): bool
            {
                $this->success = false;
                return false;
            }

            public function value(): void
            {
            }
        };

        $anonClass->pullErrorMessagesA();

        // Check the success state without calling the success() method directly.
        self::assertFalse($anonClass->success);
    }
}
