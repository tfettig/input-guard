<?php
declare(strict_types=1);

namespace InValTest;

use InVal\InVal;
use PHPUnit\Framework\TestCase;
use stdClass;

class BasicUsageTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBasicUsage(): void
    {
        $inVal = new InVal();

        $inVal->intVal(1)
              ->errorMessage('First int is invalid.');

        $inVal->intVal('error')
              ->errorMessage('Second int is invalid.');

        $inVal->floatVal(1.1)
              ->errorMessage('First float is invalid.');

        $inVal->floatVal('error')
              ->errorMessage('Second float is invalid.');

        $inVal->instanceOfVal(new stdClass(), stdClass::class)
              ->errorMessage('First instanceof is invalid.');

        $inVal->instanceOfVal('error', stdClass::class)
              ->errorMessage('Second instanceof is invalid.');


        self::assertFalse($inVal->success());
        self::assertSame(
            [
                'Second int is invalid.',
                'Second float is invalid.',
                'Second instanceof is invalid.',
            ],
            $inVal->pullErrorMessages()
        );
    }
}
