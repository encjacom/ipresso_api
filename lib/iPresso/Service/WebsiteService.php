<?php

namespace iPresso\Service;

use Itav\Component\Serializer\Serializer;

/**
 * Class WebsiteService
 * @package iPresso\Service
 */
class WebsiteService implements ServiceInterface
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
     * WebsiteService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Get monitored websites
     * @param bool|integer $idWww
     * @return bool|Response
     * @throws \Exception
     */
    public function get($idWww = false)
    {
        if ($idWww && is_numeric($idWww))
            $idWww = '/' . $idWww;
        return $this
            ->service
            ->setRequestPath('www' . $idWww)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add monitored website
     * @param string $url
     * @param array $jsApiMethods [method api key => 0|1]
     * @return bool|Response
     * @throws \Exception
     */
    public function add($url, $jsApiMethods = [])
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Set correct URL.');
        }

        foreach ($jsApiMethods as $methodKey => $isOn) {
            if (!is_string($methodKey)) {
                throw new \Exception('Js api method key must be string.');
            }
        }

        $data = [];
        $data['www']['url'] = $url;
        $data['www']['js_api_methods'] = $jsApiMethods;

        return $this
            ->service
            ->setRequestPath('www')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Delete monitored website
     * @param integer $idWww
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($idWww)
    {
        return $this
            ->service
            ->setRequestPath('www/' . $idWww)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

}