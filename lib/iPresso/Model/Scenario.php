<?php

namespace iPresso\Model;

/**
 * Class MassContactActivity
 * @package iPresso\Model
 */
class Scenario
{
    /**
     * @var array
     */
    private $contact;
    /**
     * @var array
     */
    private $contactData;
    /**
     * @var array|string array or json
     */
    private $data;

    /**
     * @param string $idContact
     * @return Scenario
     */
    public function addContact($idContact)
    {
        $this->contact[] = $idContact;
        return $this;
    }

    /**
     * @return array
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param array $contact
     * @return Scenario
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param string $idContact
     * @param array $data
     * @return Scenario
     */
    public function addContactData($idContact, $data)
    {
        $this->contactData[$idContact] = $data;
        return $this;
    }

    /**
     * @param array $contactData
     * @return Scenario
     */
    public function setContactData($contactData)
    {
        $this->contactData = $contactData;
        return $this;
    }

    /**
     * @return array
     */
    public function getContactData()
    {
        return $this->contactData;
    }

    /**
     * @param array|string $data array or json
     * @return Scenario
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array|string array or json
     */
    public function getData()
    {
        return $this->data;
    }
}