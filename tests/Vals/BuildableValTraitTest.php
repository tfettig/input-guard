<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\BuildableValTrait;
use PHPUnit\Framework\TestCase;

class BuildableValTraitTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPullErrorMessages(): void
    {
        $anonClass = new class()
        {
            use BuildableValTrait;

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
            use BuildableValTrait {
                // A hack for some weirdness in phpmd.
                BuildableValTrait::errorMessage as errorMessageA;
                BuildableValTrait::pullErrorMessages as pullErrorMessagesA;
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
