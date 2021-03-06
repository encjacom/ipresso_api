<?php

use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
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