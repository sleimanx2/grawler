<?php
namespace Bowtie\Grawler\Nodes;


use InvalidArgumentException;

abstract Class Attributes extends Collection
{

    protected $attributes = [];

    /**
     * Attributes constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $this->attributes;

        parent::__construct($items);
    }

    public function add($attributes){

        foreach($attributes as $name => $value)
        {
            $this->set($name,$value);
        }
    }


    /**
     * Gets the value of an attribute from the attributes array.
     *
     * @param mixed $default The default to return if the field doesn't exist.
     * @return mixed
     */
    public function get($name, $default = null)
    {

        if (isset($this->items[$name])) {
            return $this->items[$name];
        }

        return $default;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value)
    {

        if (isset($this->items[$name])) {
            return $this->items[$name] = $value;
        }

        throw new InvalidArgumentException('You are trying to set an invalid attribute');
    }

}