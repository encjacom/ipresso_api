<?php

namespace iPresso\Service;

use iPresso\Model\Attribute;
use iPresso\Model\AttributeOption;
use iPresso\Model\CustomerAttribute;
use Itav\Component\Serializer\Serializer;

/**
 * Class AttributeService
 * @package iPresso\Service
 */
class AttributeService implements ServiceInterface
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
     * AttributeService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Add new attributes
     * @param Attribute $attribute
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Attribute $attribute)
    {
        return $this
            ->service
            ->setRequestPath('attribute')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($attribute->getAttribute())
            ->request();
    }

    /**
     * Get available attributes
     * @return bool|Response
     * @throws \Exception
     */
    public function get()
    {
        return $this
            ->service
            ->setRequestPath('attribute')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add new options to attribute
     * @param string $attributeKey
     * @param AttributeOption $option
     * @return bool|Response
     * @throws \Exception
     */
    public function addOption($attributeKey, AttributeOption $option)
    {
        return $this
            ->service
            ->setRequestPath('attribute/' . $attributeKey . '/option')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData(['option' => [$option->getKey() => $option->getValue()]])
            ->request();
    }

    /**
     * Edit attribute option
     * @param $attributeKey
     * @param AttributeOption $option
     * @param null|string $optionKeyEdit
     * @return bool|Response
     * @throws \Exception
     */
    public function editOption($attributeKey, AttributeOption $option, $optionKeyEdit = null)
    {

        if ($optionKeyEdit) {
            $optionAttributeKey = $optionKeyEdit;
        } else {
            $optionAttributeKey = $option->getKey();
        }

        if (!$optionAttributeKey) {
            throw new \Exception('Attribute option key missing');
        }

        return $this
            ->service
            ->setRequestPath('attribute/' . $attributeKey . '/option/' . $optionAttributeKey)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData(['option' => $this->serializer->normalize($option->getOption())])
            ->request();
    }

    /**
     * @param $attributeKey
     * @param AttributeOption $option
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteOption($attributeKey, AttributeOption $option)
    {
        return $this
            ->service
            ->setRequestPath('attribute/' . $attributeKey . '/option/' . $option->getKey())
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * @return bool|Response
     * @throws \Exception
     */
    public function getCustomerAttribute()
    {
        return $this
            ->service
            ->setRequestPath('attribute/customer')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add new customer attribute
     * @param CustomerAttribute $customerAttribute
     * @return bool|Response
     * @throws \Exception
     */
    public function addCustomerAttribute(CustomerAttribute $customerAttribute)
    {
        return $this
            ->service
            ->setRequestPath('attribute/customer')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($this->serializer->normalize($customerAttribute))
            ->request();
    }

    /**
     * @param string $attributeKey
     * @param CustomerAttribute $customerAttribute
     * @return bool|Response
     * @throws \Exception
     */
    public function editCustomerAttribute(string $attributeKey, CustomerAttribute $customerAttribute)
    {
        return $this
            ->service
            ->setRequestPath('attribute/customer/' . $attributeKey)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData(['attribute' => $this->serializer->normalize($customerAttribute)])
            ->request();
    }

    /**
     * @param string $attributeKey
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteCustomerAttribute(string $attributeKey)
    {
        return $this
            ->service
            ->setRequestPath('attribute/customer/' . $attributeKey)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }
}