<?php

namespace iPresso\Service;

use Itav\Component\Serializer\Serializer;

/**
 * Class OriginService
 * @package iPresso\Service
 */
class OriginService implements ServiceInterface
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
     * OriginService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Get all contact origins
     * @return bool|Response
     * @throws \Exception
     */
    public function get()
    {
        return $this
            ->service
            ->setRequestPath('origin')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Get contacts from a given origin
     * @param integer $idOrigin
     * @param integer|bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getContact($idOrigin, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('origin/' . $idOrigin . '/contact' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }
}