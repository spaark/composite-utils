# Auto Construction

The `PropertyAccessor` provides a way to construct composites based on
properties with `@construct` annotations. This is used by the
`AutoConstructTrait` to provide a virtual constructor.

To specify a required parameter, use `@construct required`, to specify
optional constructor parameter: `@construct optional` and the specify a
property which will be built in a constructor, `@construct new`.

You can also specify an optional constructor parameter which is built
when not specified with `@construct optional new`.

The order of parameters is: all required parameters, in the order the
properties appear in the composite, followed by all optional parameters,
again in the order of their properties in the composite.

## Full example

```php
class MyAwesomeComposite
{
    use AutoConstructTrait;

    /**
     * @construct optional
     * @var DataType
     */
    protected $a;

    /**
     * @construct required
     * @var int
     */
    protected $b;

    /**
     * @construct optional new
     * @var ArrayObject
     */
    protected $c;

    /**
     * @construct optional
     * @var string
     */
    protected $d;

    /**
     * @construct new
     * @var SomeThing
     */
    protected $e;

    /**
     * Nothing will happen with this
     */
    protected $f;
}
```

This will create the following 'virtual' constructor:

```php
public function __construct
(
    int $b,
    ?DataType $a = null,
    ?ArrayObject $c = null,
    ?string $d = null
)
{
    $this->b = $b;
    
    if ($a) $this->a = $a;
    if ($c) $this->c = $c; else $this->c = new ArrayObject();
    if ($d) $this->d = $d;

    $this->e = new SomeThing();
}
```

## Using your own constructor

Often you still want to specify your own constructor logic. This is easy
to do to, simply define your construct with whatever parameters you want
and variadic parameters at the end, pass these to
`AutoConstructTrait::autoBuild()` method and the rest will be taken
care of!

### Example

```php
public function __construct($f, ...$args)
{
    $this->autoBuild(...$args);

    $this->f = doSomethingWith($f);
}
```


