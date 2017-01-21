<?php namespace Spaark\Core\Model\Reflection;
/**
 *
 *
 */

/**
 * Reflects upon properies within a model, and parses their doc comments
 */
class Property extends Reflector
{
    /**
     * This class will be instanciated to $this->object
     */
    const WRAPPER_NAME = 'ReflectionProperty';

    /**
     * List of accepted parameters in a doc comment in the form:
     *   name => function_to_call
     */
    protected $acceptedParams = array
    (
        'localkey'   => 'mixed',
        'foreignkey' => 'mixed',
        'linktable'  => 'mixed',
        'readable'   => 'bool',
        'writable'   => 'bool',
        'save'       => 'bool',
        'id'         => 'setKey',
        'var'        => 'setType',
        'standalone' => 'bool',
        'key'        => 'bool',
        'standalone' => 'bool'
    );

    /**
     * Should this value be saved to a source?
     */
    protected $save;

    /**
     * Is this property readable?
     */
    protected $readable;

    /**
     * Is this property writable?
     */
    protected $writable;

    /**
     * What kind of property is this? Primary / Unique etc
     */
    protected $key;

    /**
     *
     */
    protected $link;

    /**
     * This property's type
     */
    protected $type;

    /**
     * Will this property be an array of multiple items, or just one
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
     * Parses the doctype and infers the foreign and local keys if they
     * were not set explicitly
     *
     * @see Reflector::parse()
     */
    public function parse()
    {
        parent::parse();

        $this->object->setAccessible(true);

        if (!$this->type) return;

        if ($this->standalone === NULL)
        {
            $this->standalone = $this->type->isStandalone;
        }
    }

    /**
     * Sets the key to be primary
     *
     * @param string $name Ignored
     * @param mixed $value Ignored
     */
    protected function setKey($name, $value)
    {
        $this->key = 'primary';
    }

    /**
     * Sets the type
     *
     * This should be a model name, either on it's own or in brackets
     * with "array". Eg:
     *   + ModelName
     *   + array(ModelName)
     *
     * @param string $name Ignored
     * @param string $value The value to set
     */
    protected function setType($name, $value)
    {
        $key = NULL;

        if (!preg_match('/^array\((.*?)\)$/', $value, $match))
        {
            $type = $value;
            $many = false;
        }
        else
        {
            $type = $match[1];
            $many = true;
        }

        if (preg_match('/^(.*?)\:(.*?)$/', $type, $match))
        {
            $type = $match[1];
            $key  = $match[2];
        }

        $this->type = new Type($type, $many, $key);
    }

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
