<?php
namespace Bowtie\Grawler\Nodes;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use IteratorAggregate;

abstract Class Attributes implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * The items contained in the collection.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new collection.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $this->fill($attributes);
    }


    public function fill(array $attributes){

        foreach($attributes as $name => $value)
        {
            $this->setAttribute($name,$value);
        }

        return $this->attributes;
    }


    /**
     * Gets the value of an attribute from the attributes array.
     *
     * @param string $name The field to retrieve.
     * @param mixed $default The default to return if the field doesn't exist.
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $default;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function setAttribute($name, $value)
    {

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name] = $value;
        }

        throw new InvalidArgumentException('You are trying to set an invalid attribute');
    }

    /**
     * Returns a list of all fields set on the object.
     *
     * @return array
     */
    public function getAttributesNames()
    {
        return array_keys($this->attributes);
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->asArray();
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function asArray()
    {
        return array_map(function ($value) {
            return $value instanceof Attributes ? $value->asArray() : $value;
        }, $this->attributes);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attributes);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
       $this->setAttribute($key,$value);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->setAttribute($key,'');
    }

    /**
     * Sets a value in the items array
     *
     * @param $name
     * @return null
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    /**
     * Sets a value in the items array
     *
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }
}