<?php

namespace iPresso\Service;

use iPresso\Model\Type;
use Itav\Component\Serializer\Serializer;

/**
 * Class TypeService
 * @package iPresso\Service
 */
class TypeService implements ServiceInterface
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * TypeService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Get all contact types with their dynamic and static attributes
     * @return bool|Response
     * @throws \Exception
     */
    public function get()
    {
        return $this
            ->service
            ->setRequestPath('type')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add new type of contact
     * @param Type $type
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Type $type)
    {
        return $this
            ->service
            ->setRequestPath('type')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($type->getType())
            ->request();
    }

    /**
     * Get contacts of a given type
     * @param integer $idType
     * @param bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getContact($idType, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('type/' . $idType . '/contact' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Assignee contact type to a contact
     * @param string $typeKey
     * @param array $contactIds
     * @return bool|Response
     * @throws \Exception
     */
    public function addContact($typeKey, $contactIds)
    {
        if (!is_array($contactIds) || empty($contactIds))
            throw new \Exception('Set idContacts array first.');

        $data['contact'] = $contactIds;
        return $this
            ->service
            ->setRequestPath('type/' . $typeKey . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }
}