<?php

namespace iPresso\Model;

/**
 * Class AttributeOption
 * @package iPresso\Model
 */
class AttributeOption
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return AttributeOption
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
     * @return AttributeOption
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return AttributeOption
     * @throws \Exception
     */
    public function getOption()
    {
        if (!$this->value) {
            throw new \Exception('Attribute option value missing');
        }

        if (!$this->key) {
            throw new \Exception('Attribute option key missing');
        }

        return $this;
    }
}