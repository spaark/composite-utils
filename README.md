# Composite Utils

[![Build Status][travis-img]][travis]
[![Scrutinizer Code Quality][scrutinizer-img]][scrutinizer]
[![Code Coverage][code-coverage-img]][scrutinizer]

A set of utilities which make interacting with Composite Objects easier

## The problem

Composite objects are normally little more than a collection of
properties with meaningful names. While this is definitely better than
just using an array, this normally requires a long set of getters and
setters; these often do nothing but move the value around:

```php
public function setProperty($value)
{
    $this->property = $value;
}

public function getProperty()
{
    return $this->property;
}
```

Bloat, bloat, bloat!

That's nine lines of code to reimplement what is just
`$object->property = $value`. At best, these lines might enforce some
form of datatype value:

```php
public function setProperty(RequiredPropertyType $value)
{
    $this->property = $value;
}
```

This problem also exists in constructors, how often have you seen
something like this:

```php
public function __construct($value1, $value2, $value3, $value4, $value5)
{
    // Set values
    $this->value1 = $value1;
    $this->value2 = $value2;
    $this->value3 = $value3;
    $this->value4 = $value4;
    $this->value5 = $value5;
    
    // Init Values
    $this->value6 = new Collection();
    $this->value7 = new HashMap();
}
```

## The solution

This library introduces a set of tools to remove the need to composite
getters, setters and bloated constructors without giving up your control
over property access and data type enforcement.

```php
class MyAwesomeComposite
{
    use AutoConstructTrait;
    use PropertyAccessTrait;

    /**
     * @var int
     */
    protected $property = 42;

    /**
     * @var string
     * @readable
     * @writable
     */
    protected $stringProperty = 'some value';

    /**
     * @var DataType
     * @readable
     * @construct required
     */
     protected $iNeedThis;

    /**
     * @var ArrayObject
     * @readable
     * @construct new
     */
    protected $collection;
}
```

That's it. Nothing more required! All the properties other than
`$property` can be accessed when required, and `$collection` will be
automatically constructed for you. Although you can't see it, this
entity also has a constructor present which requires... you guessed
it... The `$iNeedThis` property, and it needs to be a `DataType`.
`$stringProperty` is also writable, if anything other than a string is
passed to it, it will be automatically cast to one if possible:

```php

$class = new MyAwesomeComposite(); // Fails, $iNeedThis is required

$iNeedThis = new OtherDataType();
$class = new MyAwesomeComposite(); // Fails, $iNeedThis should be a DataType

$iNeedThis = new DataType();
$class = new MyAwesomeComposite($iNeedThis); // All good!

var_dump($class->property); // Fails, not readable
var_dump($class->stringProperty); // string(10) "some value"

$class->stringProperty = 00000000123;
var_dump($class->stringProperty); // string(3) "123"

$class->iNeedThis = new DataType(); // Fails, not writable

var_dump($class->collection); // ArrayObject { }

var_dump($class->iNeedThis === $iNeedThis); // true
```

[travis]: https://travis-ci.org/spaark/composite-utils
[travis-img]: https://travis-ci.org/spaark/composite-utils.svg?branch=master
[scrutinizer]: https://scrutinizer-ci.com/g/spaark/composite-utils/
[scrutinizer-img]: https://scrutinizer-ci.com/g/spaark/composite-utils/badges/quality-score.png?b=master
[code-coverage-img]: https://scrutinizer-ci.com/g/spaark/composite-utils/badges/coverage.png?b=master

