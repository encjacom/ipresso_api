<?php

namespace iPresso\Service;

use iPresso\Model\Segmentation;
use Itav\Component\Serializer\Serializer;

/**
 * Class SegmentationService
 * @package iPresso\Service
 */
class SegmentationService implements ServiceInterface
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
     * SegmentationService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function getList()
    {
        return $this
            ->service
            ->setRequestPath('segmentation')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * @param Segmentation $segmentation
     * @return bool|Response
     * @throws \Exception
     */
    public function add(Segmentation $segmentation)
    {
        return $this
            ->service
            ->setRequestPath('segmentation')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($segmentation->getSegmentation())
            ->request();
    }

    /**
     * @param integer $idSegmentation
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($idSegmentation)
    {
        return $this
            ->service
            ->setRequestPath('segmentation/' . $idSegmentation)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * @param integer $idSegmentation
     * @param Segmentation $segmentation
     * @return bool|Response
     * @throws \Exception
     */
    public function addContact($idSegmentation, Segmentation $segmentation)
    {
        if (!is_numeric($idSegmentation))
            throw new \Exception('Wrong segmentation id.');

        return $this
            ->service
            ->setRequestPath('segmentation/' . $idSegmentation . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($segmentation->getSegmentationContact())
            ->request();
    }

    /**
     * @param integer $idSegmentation
     * @return Response
     * @throws \Exception
     */
    public function getContact($idSegmentation)
    {
        if (!is_numeric($idSegmentation))
            throw new \Exception('Wrong segmentation id.');

        return $this
            ->service
            ->setRequestPath('segmentation/' . $idSegmentation)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }
}