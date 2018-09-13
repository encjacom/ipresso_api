<?php

namespace iPresso\Service;

use iPresso\Model\Tag;
use Itav\Component\Serializer\Serializer;

/**
 * Class TagService
 * @package iPresso\Service
 */
class TagService implements ServiceInterface
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
     * TagService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Get tags
     * @param integer|bool $idTag
     * @return bool|Response
     * @throws \Exception
     */
    public function get($idTag = false)
    {
        if ($idTag && is_numeric($idTag))
            $idTag = '/' . $idTag;

        return $this
            ->service
            ->setRequestPath('tag' . $idTag)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add new tag
     * @param Tag $tag
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Tag $tag)
    {
        $data['name'] = $tag;
        return $this
            ->service
            ->setRequestPath('tag')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($tag->getTag())
            ->request();
    }

    /**
     * Edit selected tag
     * @param integer $idTag
     * @param Tag $tag
     * @return bool|Response
     * @throws \Exception
     */
    public function edit($idTag, Tag $tag)
    {
        return $this
            ->service
            ->setRequestPath('tag/' . $idTag)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData(['tag' => $tag->getTag()])
            ->request();
    }

    /**
     * Delete tag
     * @param integer $idTag
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($idTag)
    {
        return $this
            ->service
            ->setRequestPath('tag/' . $idTag)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Add new contacts to a tag
     * @param integer $idTag
     * @param array $contactIds
     * @return bool|Response
     * @throws \Exception
     */
    public function addContact($idTag, $contactIds)
    {
        if (!is_array($contactIds) || empty($contactIds))
            throw new \Exception('Set idContacts array first.');

        $data['contact'] = $contactIds;
        return $this
            ->service
            ->setRequestPath('tag/' . $idTag . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get all contacts in tag
     * @param integer $idTag
     * @param integer|bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getContact($idTag, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('tag/' . $idTag . '/contact' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Delete contact in tag
     * @param integer $idTag
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteContact($idTag, $idContact)
    {
        return $this
            ->service
            ->setRequestPath('tag/' . $idTag . '/contact/' . $idContact)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

}