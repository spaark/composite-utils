<?php namespace Spaark\Core\Model\Reflection;
/**
 *
 *
 */

use Spaark\Core\Model\Base\Composite;

/**
 * Reflects upon properies within a model, and parses their doc comments
 */
class Property extends Composite
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Model
     */
    protected $owner;

    /**
     * Should this value be saved to a source?
     * @var bool
     * @readable
     * @writable
     */
    protected $save;

    /**
     * Is this property readable?
     * @var bool
     * @readable
     * @writable
     */
    protected $readable;

    /**
     * Is this property writable?
     * @var bool
     * @readable
     * @writable
     */
    protected $writable;

    /**
     * What kind of property is this? Primary / Unique etc
     * @var string
     * @readable
     * @writable
     */
    protected $key;

    /**
     *
     */
    protected $link;

    /**
     * This property's type
     * @var Type
     * @readable
     * @writable
     */
    protected $type;

    /**
     * Will this property be an array of multiple items, or just one
     * @var bool
     * @readable
     * @writeable
     */
    protected $many = false;

    /**
     *
     */
    protected $from;

    /**
     * If the property is an object, it will be loaded. These specify
     * the links in the data structures which link these together.
     *
     * By default, the localkey is the property name, with '_id'
     * appended and foreignkey is just 'id'
     */
    protected $localkey, $foreignkey;

    /**
     * When links represent a many to many relationship, an intermediary
     * table can be used
     */
    protected $linktable;

    /**
     *
     */
    protected $direction;

    protected $standalone;

    /**
     * @getter
     */
    public function isProperty()
    {
        return (boolean)$this->type;
    }

    public function getValue($obj)
    {
        return $this->object->getValue($obj);
    }

    /**
     * @getter
     */
    public function name()
    {
        return $this->object->getName();
    }
}
