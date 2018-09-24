<?php

use PHPUnit\Framework\TestCase;

/**
 * Class OriginTest
 */
class OriginTest extends TestCase
{
    public static $config = [
        'url' => '',
        'login' => '',
        'password' => '',
        'customerKey' => '',
        'token' => '',
    ];

    /**
     * @var iPresso
     */
    private $class;

    /**
     * OriginTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     * @throws Exception
     */
    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->class = (new iPresso())
            ->setLogin(self::$config['login'])
            ->setPassword(self::$config['password'])
            ->setCustomerKey(self::$config['customerKey'])
            ->setToken(self::$config['token'])
            ->setUrl(self::$config['url']);
    }

    public function testOriginClass()
    {
        $this->assertInstanceOf(\iPresso\Service\OriginService::class, $this->class->origin);
    }

    /**
     * @depends testOriginClass
     * @throws Exception
     */
    public function testOriginGetAll()
    {
        $response = $this->class->origin->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('origin', $response->getData());

        return (integer)reset( $response->getData()->origin)->id;
    }

    /**
     * @depends testOriginGetAll
     * @param int $idOrigin
     * @return mixed
     * @throws Exception
     */
    public function testGetContactOrigin(int $idOrigin)
    {
        $this->assertGreaterThan(0, $idOrigin);

        $response = $this->class->origin->getContact($idOrigin);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}