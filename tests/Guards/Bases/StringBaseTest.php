<?php
declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use InputGuard\Guards\Bases\StringBase;
use PHPUnit\Framework\TestCase;

class StringBaseTest extends TestCase
{
    /**
     * @dataProvider successProvider
     *
     * @param          $input
     *
     * @param int      $min
     * @param int|null $max
     * @param string   $regex
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSuccess($input, int $min, ?int $max, string $regex, string $message): void
    {
        /** @var StringBase $val */
        $val = new class()
        {
            use StringBase {
                StringBase::minLen as minLenTraitA;
                StringBase::maxLen as maxLenTraitA;
                StringBase::betweenLen as betweenLenA;
                StringBase::regex as regexA;
                StringBase::validation as stringValidationA;
            }

            protected function extraStringValidation($input): bool
            {
                /** @noinspection SuspiciousBinaryOperationInspection */
                return $input === $input;
            }

            public function runValidation(string $input): bool
            {
                $value = null;
                return $this->validation($input, $value);
            }
        };

        $val->minLen($min)
            ->maxLen($max)
            ->regex($regex);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertTrue($val->runValidation($input), $message);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBetweenSuccess(): void
    {
        $val = new class()
        {
            use StringBase {
                StringBase::minLen as minLenTraitB;
                StringBase::maxLen as maxLenTraitB;
                StringBase::betweenLen as betweenLenB;
                StringBase::regex as regexB;
                StringBase::validation as stringValidationB;
            }

            protected function extraStringValidation($input): bool
            {
                /** @noinspection SuspiciousBinaryOperationInspection */
                return $input === $input;
            }

            public function runValidation(string $input): bool
            {
                $value = null;
                return $this->validation($input, $value);
            }
        };

        $val->betweenLen(5, 10);

        self::assertTrue($val->runValidation('success'));
    }

    /**
     * @dataProvider failureProvider
     *
     * @param mixed    $input
     * @param int      $min
     * @param int|null $max
     * @param string   $regex
     * @param string   $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFailure($input, int $min, ?int $max, string $regex, string $message): void
    {
        /** @var StringBase $val */
        $val = new class()
        {
            use StringBase {
                StringBase::minLen as minLenTraitC;
                StringBase::maxLen as maxLenTraitC;
                StringBase::betweenLen as betweenLenC;
                StringBase::regex as regexC;
                StringBase::validation as stringValidationC;
            }

            protected function extraStringValidation($input): bool
            {
                /** @noinspection SuspiciousBinaryOperationInspection */
                return $input === $input;
            }

            public function runValidation(string $input): bool
            {
                $value = null;
                return $this->validation($input, $value);
            }
        };

        $val->minLen($min)
            ->maxLen($max)
            ->regex($regex);

        /** @noinspection PhpUndefinedMethodInspection */
        self::assertFalse($val->runValidation($input), $message);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
            ['', 0, 9, '/[\w]*/', 'Empty string'],
            ['The birds', 0, 9, '/[\w]+/', 'English string'],
            ['Die Vögel', 0, null, '/[\w]+/', 'German string'],
            ['鳥たち', 0, 3, '/[\w]+/u', 'Japanese string'],
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function failureProvider(): array
    {
        return [
            ['failure', 0, 1, '/[\w]+/', 'Too long'],
            ['failure', 15, null, '/[\w]+/', 'Too short'],
            ['failure', 0, null, '/[\d]+/', 'Non-matching pattern'],
        ];
    }
}