<?php

namespace iPresso\Model;

class ContactAction
{
    const VAR_DATE = 'date';
    const VAR_KEY = 'key';
    const VAR_PARAMETER = 'parameter';
    const VAR_VALUE = 'value';

    public $contact_action;
    private $date;
    private $key;
    private $parameter = [];

    /**
     * @param string $key
     * @return ContactAction
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $date
     * @return ContactAction
     * @throws \Exception
     */
    public function setDate($date)
    {
        try {
            $date = (new \DateTime($date))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $this->date = $date;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return ContactAction
     */
    public function addParameter($key, $value)
    {
        $this->parameter[$key] = $value;
        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getContactAction()
    {
        if (empty($this->key))
            throw new \Exception('Wrong action key.');

        $this->contact_action[self::VAR_KEY] = $this->key;

        if (!empty($this->parameter))
            $this->contact_action[self::VAR_PARAMETER] = $this->parameter;

        if (empty($this->date))
            $this->date = date('Y-m-d H:i:s');

        $this->contact_action[self::VAR_DATE] = $this->date;
        return $this->contact_action;
    }
}