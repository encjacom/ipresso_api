<?php

use PHPUnit\Framework\TestCase;

/**
 * Class WebsiteTest
 */
class WebsiteTest extends TestCase
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
     * WebsiteTest constructor.
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

    public function testWebsiteClass()
    {
        $this->assertInstanceOf(\iPresso\Service\WebsiteService::class, $this->class->www);
    }

    /**
     * @throws Exception
     */
    public function testWebsiteAddWrong()
    {
        $this->expectException(Exception::class);
        $this->class->www->add('test');
    }

    /**
     * @depends testWebsiteClass
     * @throws Exception
     */
    public function testWebsiteGetAll()
    {
        $response = $this->class->www->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('www', $response->getData());
    }

    /**
     * @return int
     * @throws Exception
     */
    public function testWebsiteAdd()
    {
        $response = $this->class->www->add('https://test.pl');

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('www', $response->getData());

        return (integer)$response->getData()->www->id;
    }


    /**
     * @depends testWebsiteAdd
     * @param int $idWebsite
     * @return int
     * @throws Exception
     */
    public function testWebsiteGet(int $idWebsite)
    {
        $this->assertGreaterThan(0, $idWebsite);

        $response = $this->class->www->get($idWebsite);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('www', $response->getData());

        $this->assertEquals($idWebsite, $response->getData()->www->id);

        return (integer)$response->getData()->www->id;
    }

    /**
     * @depends testWebsiteGet
     * @param int $idWebsite
     * @throws Exception
     */
    public function testWebsiteDelete(int $idWebsite)
    {
        $this->assertGreaterThan(0, $idWebsite);

        $response = $this->class->www->delete($idWebsite);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}