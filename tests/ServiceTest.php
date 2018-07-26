<?php

use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public static $config = [
        'url' => 'https://panel.local-ipresso.encja.eu',
        'login' => 'api',
        'password' => 'api.API.123',
        'customerKey' => 'c4ca4238a0b923820dcc509a6f75849b',
        'token' => 'd9916ea09b85e8ad111adb602edf99d7',
    ];

    /**
     * @var iPresso
     */
    private $class;

    /**
     * ServiceTest constructor.
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
            ->setUrl(self::$config['url']);
    }

    /**
     * @throws Exception
     */
    public function testSetWrongUrl()
    {
        $this->expectException(Exception::class);
        $class = new iPresso();
        $class->setUrl('http://panel.ipresso.dev');
    }

    /**
     * @depends testSetWrongUrl
     * @throws Exception
     */
    public function testGetToken()
    {
        $token = $this->class->getToken();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $token);

        $this->assertEquals(self::$config['token'], $token->getData());
    }

}