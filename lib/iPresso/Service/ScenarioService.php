<?php

namespace iPresso\Service;

use iPresso\Model\Scenario;
use Itav\Component\Serializer\Serializer;

/**
 * Class ActionService
 * @package iPresso\Service
 */
class ScenarioService implements ServiceInterface
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
     * Add contacts to scenario
     * @param string $key
     * @param Scenario $scenario
     * @return bool|Response
     * @throws \Exception
     */
    public function addContacts($key, Scenario $scenario)
    {
        if (!$key) {
            throw new \Exception('Scenario key is missing.');
        }

        return $this
            ->service
            ->setRequestPath('scenario/' . $key . '/contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($this->serializer->normalize($scenario, false, false))
            ->request();
    }

    /**
     * Add contacts to scenario
     * @param string $key
     * @param Scenario|null $scenario
     * @return bool|Response
     * @throws \Exception
     */
    public function startScenario($key, $scenario = null)
    {
        if (!$key) {
            throw new \Exception('Scenario key is missing.');
        }

        return $this
            ->service
            ->setRequestPath('scenario/' . $key . '/start')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($this->serializer->normalize($scenario, false, false))
            ->request();
    }

    /**
     * Add contacts to scenario
     * @param string $key
     * @return bool|Response
     * @throws \Exception
     */
    public function holdScenario($key)
    {
        if (!$key) {
            throw new \Exception('Scenario key is missing.');
        }

        return $this
            ->service
            ->setRequestPath('scenario/' . $key . '/hold')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->request();
    }
}