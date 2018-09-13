<?php

use PHPUnit\Framework\TestCase;

/**
 * Class TypeTest
 */
class TypeTest extends TestCase
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
     * TypeTest constructor.
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

    public function testTypeClass()
    {
        $this->assertInstanceOf(\iPresso\Service\TypeService::class, $this->class->type);
    }

    /**
     * @throws Exception
     */
    public function testTypeAddWrong()
    {
        $type = new \iPresso\Model\Type();

        $this->expectException(Exception::class);
        $type->getType();
    }

    /**
     * @depends testTypeClass
     */
    public function testTypeGetAll()
    {
        $response = $this->class->type->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('type', $response->getData());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function testTypeAdd()
    {
        $type = new \iPresso\Model\Type();
        $type->setName('Unit tests');
        $type->setKey('unit_tests');
        $response = $this->class->type->add($type);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('type', $response->getData());

        return (string)$response->getData()->type->key;
    }


    /**
     * @return integer
     * @throws Exception
     */
    public function testContactAdd()
    {
        $contact = new \iPresso\Model\Contact();
        $contact->setEmail('michal.per+test@encja.com');

        $response = $this->class->contact->add($contact);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('contact', $response->getData());

        $contact = reset($response->getData()->contact);

        $this->assertContains($contact->code, [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND, \iPresso\Service\Response::STATUS_SEE_OTHER]);

        $this->assertGreaterThan(0, $contact->id);

        return (integer)$contact->id;
    }

    /**
     * @depends testTypeAdd
     * @depends testContactAdd
     * @param string $typeKey
     * @param int $idContact
     * @throws Exception
     */
    public function testAddContactToType(string $typeKey, int $idContact)
    {
        $this->assertNotEmpty($typeKey);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->type->addContact($typeKey, [$idContact]);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testTypeAdd
     * @depends testContactAdd
     * @depends testAddContactToType
     * @param string $typeKey
     * @param int $idContact
     * @throws Exception
     */
    public function testGetContactInType(string $typeKey, int $idContact)
    {
        $this->assertNotEmpty($typeKey);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->type->getContact($typeKey);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('id', $response->getData());

        $this->assertContains($idContact, $response->getData()->id);
    }
}