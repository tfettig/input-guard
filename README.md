# InputGuard
[![Build Status](https://travis-ci.com/tfettig01/InputGuard.svg?branch=master)](https://travis-ci.com/tfettig01/InputGuard) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tfettig01/InputGuard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tfettig01/InputGuard/?branch=master)[![Code Coverage](https://scrutinizer-ci.com/g/tfettig01/InputGuard/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tfettig01/InputGuard/?branch=master)

InputGuard is a stand alone validation library using a fluid interface to validate inputs.

This project is currently an unstable pre-alpha. Tags will be added when stability is achieved.

Simple Demonstration:
```php
<?php
declare(strict_types=1);

$validation = new InputGuard\InputGuard();

$validation->int(1)
           ->min(0)
           ->errorMessage('The integer is invalid.');

$validation->float(.6)
           ->between(.1, .9)
           ->errorMessage('The float is invalid.');

$validation->string('A string value that needs to be validated.')
           ->errorMessage('The string is invalid.')
           ->regex('/^[\w .]+$/')
           ->minLen(0)
           ->maxLen(500);

$validation->iterableString(['Multiple', 'strings', 'that', null, 'will', 'validate', 'successfully', ''])
           ->allowNull()
           ->allowNullElement()
           ->allowEmptyString()
           ->betweenCount(1, 10)
           ->betweenLen(0, 500)
           ->errorMessage('The array is invalid.')
           ->errorMessage('The array is super invalid.');

$validation->add(new class implements \InputGuard\Guards\Guard
{
    use \InputGuard\Guards\ErrorMessagesBase;

    public function success(): bool
    {
        return true;
    }

    public function value(): string
    {
        return 'A custom validation object.';
    }
})->errorMessage('Custom Guard is invalid.');

assert($validation->success());
assert(count($validation->pullErrorMessages()) === 0);
```

Advanced Demonstration: [Demonstration Unit Test](https://github.com/tfettig01/InputGuard/blob/master/tests/DemonstrationTest.php).

Validators plan:
- [x] Add boolean validator.
- [x] Add integer validator.
- [x] Add float validator.
- [x] Add iterable validator.
- [x] Add string validator.
- [x] Add stringable validator (an object that implements __toString).
- [x] Add instance of validator.
- [x] Add list validator.

Validator feature plan:
- [ ] Add strict() and nonStrict() methods to integer, float, string, and stringable validation (type juggling).

Iterable validator plan:
- [x] Validation of values in an iterable of integers.
- [x] Validation of values in an iterable of strings.
- [x] Validation of values in an iterable of stringable.
- [x] Validation of values in an iterable of floats.
- [x] Allow elements in the iterable to be null.
- [ ] Allow elements in the iterable to be empty strings.

String validator plan
- [ ] SECURITY: Add helper method for for common XSS injections.
- [ ] Set language encoding.
- [ ] Country locales (using Locale).
- [ ] State/Providence codes.

Renaming plan:
- [x] Move base traits into their own directories.
- [x] Rename InVal\Vals\*Val to InVal\Guards\*Guard.
- [x] Rename InVal\* to InputGuard\*.
- [x] Rename InVal to InputGuard.
- [x] Rename repository to InputGuard.
- [x] Update CI build and badge to InputGuard.

Library plan:
- [ ] Figure out input types possibilities: NonStrict, Strict with null/NullObject, and Strict.
