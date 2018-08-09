# InputGuard
[![Build Status](https://travis-ci.com/tfettig01/InputGuard.svg?branch=master)](https://travis-ci.com/tfettig01/InputGuard) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tfettig01/InputGuard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tfettig01/InputGuard/?branch=master)[![Code Coverage](https://scrutinizer-ci.com/g/tfettig01/InputGuard/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tfettig01/InputGuard/?branch=master)

InputGuard is a stand alone validation library using a builder interface to validate inputs.

This project is currently an unstable pre-alpha. Tags will be added when stability is achieved.

Demonstration Usage: [DemonstrationTest Usage](https://github.com/tfettig01/InputGuard/blob/master/tests/DemonstrationTest.php).

```php
<?php
declare(strict_types=1);

use InputGuard\InputGuard;

$validation = new InputGuard();

$validation->int(1)
           ->min(0)
           ->errorMessage('This message will not be present on validation.');

$validation->float(.6)
           ->between(.1, .9)
           ->errorMessage('This message will not be present on validation.');

$validation->string('A string value that needs to be validated.')
           ->errorMessage('This message will not be present on validation.')
           ->regex('/^[\w .]+$/')
           ->minLen(0)
           ->maxLen(500);

assert($validation->success());
assert(count($validation->pullErrorMessages()) === 0);
```

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
