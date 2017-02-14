# Property Access

There are three kinds of Property Accessor Services provided by this
package:

+ `RawPropertyAccessor`
+ `PropertyAccessor`
+ `ConditionalPropertyAccessor`

## Raw Property Accessor

This provides direct access to a composite's properties, regardless of
their visibility. This is typically useful for Factories which can be
trusted to populate an entity with sensible data and need unencumbered
access.

This service is also used by the `AllReadableTrait` which allows all
properties on a composite to be read, but none to be written. This is
useful for objects which are immutable once constructed, especially as
the `RawPropertyAccessor` does not need to read any annotations so you
can skip some overhead there.

## Property Accessor

This provides access to all of a composite's properties again regardless
of their visibility. The one difference is that data type requirements
are enforced. That is, if a property has been declared with a certain
datatype using the `@var` annotation, the `PropertyAccessor` will throw
an exception if the provided data type is incorrect.

### Casting

In cases where a scalar data type has been declared, `PropertyAccessor`
will accept object inputs if they can be casted using a `__toXXX()`
method. For example, an object passed to a `string` property will have
its `__toString()` method called and the value of that used. The same
goes for `__toBoolean()` and `__toInteger()`.

### Auto Constructing

This service is used by the `AutoConstructTrait` for auto construction.
See the [Auto Construction Documentation](Auto-Construction.md).

## Conditional Property Accessor

Like the `PropertyAccessor`, the `ConditionalPropertyAccessor` enforces
required data types, with the addition of access rules. These can be set
using the `@readable` and `@writable` annotations.

This service is used by the `PropertyAccessTrait` which provides `__get`
and `__set` magic methods.
