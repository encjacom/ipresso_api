<?php

namespace iPresso\Service;

use Itav\Component\Serializer\Serializer;

/**
 * Class SourceService
 * @package iPresso\Service
 */
class SourceService implements ServiceInterface
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
     * SourceService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Get all contact sources
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
     * Get contacts from a given source
     * @param integer $idSource
     * @param integer|bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getContact($idSource, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('origin/' . $idSource . '/contact' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }
}