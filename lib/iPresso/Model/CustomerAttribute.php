<?php

namespace iPresso\Model;

/**
 * Class CustomerAttribute
 * @package iPresso\Model
 */
class CustomerAttribute
{
    /**
     * CUSTOMER ATTRIBUTE TYPES
     */
    const TYPE_DECIMAL = 'text';
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CustomerAttribute
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return CustomerAttribute
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return CustomerAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return CustomerAttribute
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}