<?php

namespace iPresso\Service;

use iPresso\Model\ContactActivity;
use Itav\Component\Serializer\Serializer;

/**
 * Class ContactAnonymousService
 * @package iPresso\Service
 */
class ContactAnonymousService implements ServiceInterface
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
     * ContactService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Adding activity to a anonymous contact
     * @see https://apidocpl.ipresso.com/
     * @param $idContactAnonymous
     * @param ContactActivity $contactActivity
     * @return bool|Response
     * @throws \Exception
     */
    public function addActivity($idContactAnonymous, ContactActivity $contactActivity)
    {
        $data['activity'][] = $contactActivity->getContactActivity();
        return $this
            ->service
            ->setRequestPath('anonymous/' . $idContactAnonymous . '/activity')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Adding activities to a anonymous contact
     * @see https://apidocpl.ipresso.com/
     * @param $idContactAnonymous
     * @param ContactActivity[] $contactActivities
     * @return bool|Response
     * @throws \Exception
     */
    public function addActivities($idContactAnonymous, $contactActivities)
    {
        $data = [];
        foreach ($contactActivities as $contactActivity) {
            if ($contactActivity instanceof ContactActivity)
                $data['activity'][] = $contactActivity->getContactActivity();
        }

        return $this
            ->service
            ->setRequestPath('anonymous/' . $idContactAnonymous . '/activity')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }


}