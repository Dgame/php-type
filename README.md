# php-type

[![Build Status](https://travis-ci.org/Dgame/php-type.svg?branch=master)](https://travis-ci.org/Dgame/php-type)
[![Build Status](https://scrutinizer-ci.com/g/Dgame/php-type/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-type/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Dgame/php-type/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-type/?branch=master)
[![codecov](https://codecov.io/gh/Dgame/php-type/branch/master/graph/badge.svg)](https://codecov.io/gh/Dgame/php-type)
[![StyleCI](https://styleci.io/repos/68286580/shield?branch=master)](https://styleci.io/repos/68286580)

Type comparisons on a whole new level.
Get the type of a variable, prove that the type is builtin or compare it with another type.

## Methods

### is
```php
$this->assertTrue(typeof(0.0)->isFloat());
$this->assertTrue(typeof(0)->isInt());
$this->assertTrue(typeof('')->isString());
$this->assertTrue(typeof('a')->isString());
$this->assertTrue(typeof('0')->isNumeric());
$this->assertTrue(typeof([])->isArray());
$this->assertTrue(typeof(new Exception())->isObject());
$this->assertFalse(typeof(null)->isObject());
$this->assertTrue(typeof(null)->isNull());
```

### equals
```php
$this->assertTrue(typeof(new Exception())->equals(new Exception()));
$this->assertTrue(typeof(null)->equals(null));
```

### isImplicit
```php
$this->assertTrue(typeof(0.0)->isImplicit(Type::IS_INT));
$this->assertTrue(typeof(0.0)->isImplicit(Type::IS_FLOAT));
$this->assertTrue(typeof(0.0)->isImplicit(Type::IS_STRING));
$this->assertTrue(typeof(0.0)->isImplicit(Type::IS_NUMERIC));
$this->assertTrue(typeof(0.0)->isImplicit(Type::IS_BOOL));
$this->assertTrue(typeof('0')->isImplicit(Type::IS_INT));
$this->assertTrue(typeof('0')->isImplicit(Type::IS_FLOAT));
$this->assertTrue(typeof('0')->isImplicit(Type::IS_BOOL));
$this->assertTrue(typeof('0')->isImplicit(Type::IS_NUMERIC));
$this->assertTrue(typeof('0')->isImplicit(Type::IS_STRING));
$this->assertTrue(typeof(false)->isImplicit(Type::IS_INT));
$this->assertTrue(typeof(false)->isImplicit(Type::IS_FLOAT));
$this->assertTrue(typeof(false)->isImplicit(Type::IS_BOOL));
$this->assertTrue(typeof(false)->isImplicit(Type::IS_STRING));
```

### isBuiltin
```php
$this->assertTrue(typeof(0)->isBuiltin());
$this->assertTrue(typeof(0.0)->isBuiltin());
$this->assertTrue(typeof(true)->isBuiltin());
$this->assertTrue(typeof('a')->isBuiltin());
$this->assertTrue(typeof('0')->isBuiltin());
$this->assertTrue(typeof([])->isBuiltin());
$this->assertFalse(typeof(new Exception())->isBuiltin());
```

### isSame
```php
$this->assertTrue(typeof(0.0)->isSame(typeof(3.14)));
$this->assertFalse(typeof(0.0)->isSame(typeof(3)));
$this->assertTrue(typeof(0)->isSame(typeof(4)));
$this->assertFalse(typeof(0)->isSame(typeof(3.14)));
$this->assertTrue(typeof('a')->isSame(typeof('b')));
$this->assertFalse(typeof('a')->isSame(typeof('0')));
$this->assertFalse(typeof('a')->isSame(typeof(0)));
$this->assertTrue(typeof('0')->isSame(typeof('42')));
$this->assertFalse(typeof('0')->isSame(typeof('a')));
$this->assertFalse(typeof('0')->isSame(typeof(0)));
```

### isImplicitSame
```php
$this->assertTrue(typeof(0.0)->isImplicitSame(typeof(0)));
$this->assertTrue(typeof(0)->isImplicitSame(typeof(0.0)));
$this->assertTrue(typeof(0)->isImplicitSame(typeof('4')));
$this->assertTrue(typeof('a')->isImplicitSame(typeof('b')));
$this->assertFalse(typeof('a')->isImplicitSame(typeof(42)));
$this->assertTrue(typeof('0')->isImplicitSame(typeof(42)));
```

### getDefaultValue
```php
$this->assertEquals(0, typeof(42)->getDefaultValue());
$this->assertEquals(0.0, typeof(2.3)->getDefaultValue());
$this->assertEquals('', typeof('abc')->getDefaultValue());
$this->assertEquals(false, typeof(true)->getDefaultValue());
$this->assertEquals([], typeof([1, 2, 3])->getDefaultValue());
$this->assertEquals(null, typeof(null)->getDefaultValue());
$this->assertEquals(null, typeof(new Exception())->getDefaultValue());
```

### export
```php
$this->assertEquals('int', typeof(42)->export());
$this->assertEquals('float', typeof(2.3)->export());
$this->assertEquals('string', typeof('abc')->export());
$this->assertEquals('bool', typeof(true)->export());
$this->assertEquals('array', typeof([1, 2, 3])->export());
$this->assertEquals('null', typeof(null)->export());
$this->assertEquals('object', typeof(new Exception())->export());
```

### isEmptyValue
You dislike that PHP treats 0 / '0' as an empty value? Here is the solution:

```php
$this->assertTrue(Type::isEmptyValue(''));
$this->assertFalse(Type::isEmptyValue(' '));
$this->assertFalse(Type::isEmptyValue('abc'));
$this->assertFalse(Type::isEmptyValue('0'));
$this->assertFalse(Type::isEmptyValue(0));
$this->assertFalse(Type::isEmptyValue(false));
$this->assertFalse(Type::isEmptyValue(true));
$this->assertTrue(Type::isEmptyValue(null));
$this->assertTrue(Type::isEmptyValue([]));
$this->assertFalse(Type::isEmptyValue([1, 2, 3]));
```

### isValidValue
Still want that false is _empty_? Try `isValidValue`

```php
$this->assertFalse(Type::isValidValue(''));
$this->assertTrue(Type::isValidValue(' '));
$this->assertTrue(Type::isValidValue('abc'));
$this->assertTrue(Type::isValidValue('0'));
$this->assertTrue(Type::isValidValue(0));
$this->assertFalse(Type::isValidValue(false));
$this->assertTrue(Type::isValidValue(true));
$this->assertFalse(Type::isValidValue(null));
$this->assertFalse(Type::isValidValue([]));
$this->assertTrue(Type::isValidValue([1, 2, 3]));
```
