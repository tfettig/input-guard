<?php
declare(strict_types=1);

namespace InValTest\Vals;

use InVal\Vals\StringTrait;
use InVal\Vals\StringValidatable;
use PHPUnit\Framework\TestCase;

class StringTraitTest extends TestCase
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
        $val = new class() implements StringValidatable
        {
            use StringTrait {
                StringTrait::minLen as minLenTraitA;
                StringTrait::maxLen as maxLenTraitA;
                StringTrait::betweenLen as betweenLenA;
                StringTrait::regex as regexA;
                StringTrait::validation as stringValidationA;
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

        self::assertTrue($val->runValidation($input), $message);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBetweenSuccess(): void
    {
        $val = new class() implements StringValidatable
        {
            use StringTrait {
                StringTrait::minLen as minLenTraitB;
                StringTrait::maxLen as maxLenTraitB;
                StringTrait::betweenLen as betweenLenB;
                StringTrait::regex as regexB;
                StringTrait::validation as stringValidationB;
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
        $val = new class() implements StringValidatable
        {
            use StringTrait {
                StringTrait::minLen as minLenTraitC;
                StringTrait::maxLen as maxLenTraitC;
                StringTrait::betweenLen as betweenLenC;
                StringTrait::regex as regexC;
                StringTrait::validation as stringValidationC;
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

        self::assertFalse($val->runValidation($input), $message);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function successProvider(): array
    {
        return [
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
