<?php

namespace iPresso\Service;

use iPresso\Model\Activity;
use Itav\Component\Serializer\Serializer;

/**
 * Class ActivityService
 * @package iPresso\Service
 */
class ActivityService implements ServiceInterface
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
     * ActivityService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Add new activity
     * @param Activity $activity
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Activity $activity)
    {
        return $this
            ->service
            ->setRequestPath('activity')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($activity->getActivity())
            ->request();
    }

    /**
     * @param string $activityKey
     * @param Activity $activity
     * @return bool|Response
     * @throws \Exception
     */
    public function edit($activityKey, Activity $activity)
    {
        return $this
            ->service
            ->setRequestPath('activity/' . $activityKey)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData($this->serializer->normalize($activity))
            ->request();
    }

    /**
     * @param string $activityKey
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($activityKey)
    {
        return $this
            ->service
            ->setRequestPath('activity/' . $activityKey)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Get available activities
     * @return bool|Response
     * @throws \Exception
     */
    public function get()
    {
        return $this
            ->service
            ->setRequestPath('activity')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * @param string $activityKey
     * @param array $contactIds
     * @param array $parameters
     * @param \DateTime|false $dateTime
     * @return bool|Response
     * @throws \Exception
     */
    public function addContact(string $activityKey, array $contactIds, array $parameters = [], $dateTime = false)
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
            ->setRequestPath('activity/' . $activityKey . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }
}