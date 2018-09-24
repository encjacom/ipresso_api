<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ConnectionTest
 */
class ConnectionTest extends TestCase
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
     * ConnectionTest constructor.
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

    /**
     * @return string
     * @throws Exception
     */
    public function testTypeAddParent()
    {
        $type = new \iPresso\Model\Type();
        $type->setName('Unit tests - Parent');
        $type->setKey('unit_tests_parent');
        $response = $this->class->type->add($type);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('type', $response->getData());

        return (string)$response->getData()->type->key;
    }

    /**
     * @depends testTypeAddParent
     * @param string $contactTypeParent
     * @return string
     * @throws Exception
     */
    public function testTypeAddChild(string $contactTypeParent)
    {
        $type = new \iPresso\Model\Type();
        $type->setName('Unit tests - Child');
        $type->setKey('unit_tests_child');
        $type->setParent($contactTypeParent);
        $response = $this->class->type->add($type);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('type', $response->getData());

        return (string)$response->getData()->type->key;
    }


    /**
     * @depends testTypeAddParent
     * @param string $contactType
     * @return integer
     * @throws Exception
     */
    public function testContactAddParent(string $contactType)
    {
        $contact = new \iPresso\Model\Contact();
        $contact->setEmail('michal.per+parent@encja.com');
        $contact->setType($contactType);

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
     * @depends testTypeAddChild
     * @param string $contactType
     * @return integer
     * @throws Exception
     */
    public function testContactAddChild(string $contactType)
    {
        $contact = new \iPresso\Model\Contact();
        $contact->setEmail('michal.per+child@encja.com');
        $contact->setType($contactType);

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
     * @depends testContactAddParent
     * @depends testContactAddChild
     * @param int $idContactParent
     * @param int $idContactChild
     * @throws Exception
     */
    public function testContactSetConnection(int $idContactParent, int $idContactChild)
    {
        $this->assertGreaterThan(0, $idContactParent);

        $this->assertGreaterThan(0, $idContactChild);

        $response = $this->class->contact->setConnection($idContactParent, $idContactChild);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testContactAddParent
     * @depends testContactAddChild
     * @depends testContactSetConnection
     * @param int $idContactParent
     * @param int $idContactChild
     * @throws Exception
     */
    public function testContactGetConnection(int $idContactParent, int $idContactChild)
    {
        $this->assertGreaterThan(0, $idContactParent);

        $this->assertGreaterThan(0, $idContactChild);

        $response = $this->class->contact->getConnection($idContactParent);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('connection', $response->getData());

        $this->assertContains($idContactChild, $response->getData()->connection);
    }

    /**
     * @depends testContactAddParent
     * @depends testContactAddChild
     * @depends testContactGetConnection
     * @param int $idContactParent
     * @param int $idContactChild
     * @throws Exception
     */
    public function testContactDeleteConnection(int $idContactParent, int $idContactChild)
    {
        $this->assertGreaterThan(0, $idContactParent);

        $this->assertGreaterThan(0, $idContactChild);

        $response = $this->class->contact->deleteConnection($idContactParent, $idContactChild);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }

}