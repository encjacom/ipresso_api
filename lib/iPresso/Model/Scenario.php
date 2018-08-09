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
}