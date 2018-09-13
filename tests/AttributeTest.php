<?php

use PHPUnit\Framework\TestCase;

/**
 * Class AttributeTest
 */
class AttributeTest extends TestCase
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
     * AttributeTest constructor.
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

    public function testAttributeClass()
    {
        $this->assertInstanceOf(\iPresso\Service\AttributeService::class, $this->class->attribute);
    }

    /**
     * @throws Exception
     */
    public function testAttributeAddWrong()
    {
        $type = new \iPresso\Model\Attribute();

        $this->expectException(Exception::class);
        $type->getAttribute();
    }

    /**
     * @depends testAttributeClass
     * @throws Exception
     */
    public function testAttributeGetAll()
    {
        $response = $this->class->attribute->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('attribute', $response->getData());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function testAttributeAdd()
    {
        $attribute = new \iPresso\Model\Attribute();
        $attribute->setName('Unit tests');
        $attribute->setKey('unit_tests');
        $attribute->setType(\iPresso\Model\Attribute::TYPE_MULTI_SELECT);
        $response = $this->class->attribute->add($attribute);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('attribute', $response->getData());

        return (string)$response->getData()->attribute->key;
    }

    /**
     * @depends testAttributeAdd
     * @param string $attributeKey
     * @return string
     * @throws Exception
     */
    public function testAttributeOptionAdd(string $attributeKey)
    {
        $this->assertNotEmpty($attributeKey);

        $attribute = new \iPresso\Model\AttributeOption();
        $attribute->setValue('option1');
        $attribute->setKey('key1');

        $response = $this->class->attribute->addOption($attributeKey, $attribute);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        return (string)$attribute->getKey();
    }

    /**
     * @depends testAttributeAdd
     * @depends testAttributeOptionAdd
     * @param string $attributeKey
     * @param string $optionKey
     * @return string
     * @throws Exception
     */
    public function testAttributeOptionEdit(string $attributeKey, string $optionKey)
    {
        $this->assertNotEmpty($attributeKey);
        $this->assertNotEmpty($optionKey);

        $attribute = new \iPresso\Model\AttributeOption();
        $attribute->setValue('option1edited');
        $attribute->setKey($optionKey);

        $response = $this->class->attribute->editOption($attributeKey, $attribute);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        return (string)$attribute->getKey();
    }

    /**
     * @depends testAttributeAdd
     * @depends testAttributeOptionAdd
     * @param string $attributeKey
     * @param string $optionKey
     * @throws Exception
     */
    public function testAttributeOptionDelete(string $attributeKey, string $optionKey)
    {
        $this->assertNotEmpty($attributeKey);
        $this->assertNotEmpty($optionKey);

        $attribute = new \iPresso\Model\AttributeOption();
        $attribute->setKey($optionKey);

        $response = $this->class->attribute->deleteOption($attributeKey, $attribute);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }

}