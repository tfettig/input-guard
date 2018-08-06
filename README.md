# InVal
[![Build Status](https://travis-ci.com/tfettig01/InVal.svg?branch=master)](https://travis-ci.com/tfettig01/InVal)

InvVal is a stand alone validation library using a builder interface to validate values.

This project is currently an unstable pre-alpha. Tags will be added when stability is achieved.

Demonstration Usage: [DemonstrationTest Usage](https://github.com/tfettig01/InVal/blob/master/tests/DemonstrationTest.php).

```php
<?php
declare(strict_types=1);

use InVal\InVal;

$validation = new InVal();

$validation->intVal(1)
           ->min(0)
           ->errorMessage('This message will not be present on validation.');

$validation->floatVal(.6)
           ->between(.1, .9)
           ->errorMessage('This message will not be present on validation.');

$validation->stringVal('A string value that needs to be validated.')
           ->errorMessage('This message will not be present on validation.')
           ->regex('/^[\w .]+$/')
           ->minLen(0)
           ->maxLen(500);

assert($validation->success());
assert(count($validation->pullErrorMessages()) === 0);
```

Validator plan:
- [x] Add boolean validator
- [x] Add integer validator
- [x] Add float validator
- [x] Add iterable validator
- [x] Add string validator
- [x] Add stringable validator (an object that implements __toString)
- [x] Add instance of validator
- [x] Add list validator

Iterable validator plan:
- [x] Validation of values in an iterable of integers
- [x] Validation of values in an iterable of strings
- [ ] Validation of values in an iterable of stringable
- [ ] Validation of values in an iterable of floats

String validator plan
- [ ] Country locales (using Locale)
- [ ] State/Providence codes