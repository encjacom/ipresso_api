<?php

namespace iPresso\Service;

use iPresso\Model\Category;
use Itav\Component\Serializer\Serializer;

/**
 * Class CategoryService
 * @package iPresso\Service
 */
class CategoryService implements ServiceInterface
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
     * CategoryService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Get category
     * @param integer|bool $idCategory
     * @return bool|Response
     * @throws \Exception
     */
    public function get($idCategory = false)
    {
        if ($idCategory && is_numeric($idCategory))
            $idCategory = '/' . $idCategory;

        return $this
            ->service
            ->setRequestPath('category' . $idCategory)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add new category
     * @param Category $category
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Category $category)
    {
        return $this
            ->service
            ->setRequestPath('category')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($category->getCategory())
            ->request();
    }

    /**
     * Edit selected category
     * @param integer $idCategory
     * @param Category $category
     * @return bool|Response
     * @throws \Exception
     */
    public function edit($idCategory, Category $category)
    {
        return $this
            ->service
            ->setRequestPath('category/' . $idCategory)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData(['category' => $category->getCategory()])
            ->request();
    }

    /**
     * Delete category
     * @param integer $idCategory
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($idCategory)
    {
        return $this
            ->service
            ->setRequestPath('category/' . $idCategory)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Add new contacts to categories
     * @param integer $idCategory
     * @param array $contactIds
     * @return bool|Response
     * @throws \Exception
     */
    public function addContact($idCategory, $contactIds)
    {
        if (!is_array($contactIds) || empty($contactIds))
            throw new \Exception('Set idContacts array first.');

        $data['contact'] = $contactIds;
        return $this
            ->service
            ->setRequestPath('category/' . $idCategory . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get all contacts in category
     * @param integer $idCategory
     * @param integer|bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getContact($idCategory, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('category/' . $idCategory . '/contact' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Delete contact in category
     * @param integer $idCategory
     * @param integer $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteContact($idCategory, $idContact)
    {
        return $this
            ->service
            ->setRequestPath('category/' . $idCategory . '/contact/' . $idContact)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }
}