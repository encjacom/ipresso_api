<?php

namespace iPresso\Service;

use iPresso\Model\Action;
use Itav\Component\Serializer\Serializer;

/**
 * Class ActionService
 * @package iPresso\Service
 */
class ActionService implements ServiceInterface
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
     * ActionService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Add new actions
     * @param Action $action
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Action $action)
    {
        return $this
            ->service
            ->setRequestPath('action')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($action->getAction())
            ->request();
    }

    /**
     * Get available actions
     * @return bool|Response
     * @throws \Exception
     */
    public function get()
    {
        return $this
            ->service
            ->setRequestPath('action')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }


    /**
     * @param string $actionKey
     * @param Action $action
     * @return bool|Response
     * @throws \Exception
     */
    public function edit($actionKey, Action $action)
    {
        return $this
            ->service
            ->setRequestPath('action/' . $actionKey)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData($this->serializer->normalize($action))
            ->request();
    }

    /**
     * @param string $actionKey
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($actionKey)
    {
        return $this
            ->service
            ->setRequestPath('action/' . $actionKey)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }


    /**
     * @param string $actionKey
     * @param array $contactIds
     * @param array $parameters
     * @param \DateTime|false $dateTime
     * @return bool|Response
     * @throws \Exception
     */
    public function addContact(string $actionKey, array $contactIds, array $parameters = [], $dateTime = false)
    {
        if (!is_array($contactIds) || empty($contactIds))
            throw new \Exception('Set idContacts array first.');

        $data = [
            'contact' => $contactIds,
            'parameter' => $parameters,
            'date' => $dateTime instanceof \DateTime ? $dateTime->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
        ];
        return $this
            ->service
            ->setRequestPath('action/' . $actionKey . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }
}