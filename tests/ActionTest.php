<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ActionTest
 */
class ActionTest extends TestCase
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
     * ActionTest constructor.
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

    public function testActionClass()
    {
        $this->assertInstanceOf(\iPresso\Service\ActionService::class, $this->class->action);
    }

    /**
     * @throws Exception
     */
    public function testActionAddWrong()
    {
        $action = new \iPresso\Model\Action();

        $this->expectException(Exception::class);
        $action->getAction();
    }

    /**
     * @depends testActionClass
     * @throws Exception
     */
    public function testActionGetAll()
    {
        $response = $this->class->action->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('action', $response->getData());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function testActionAdd()
    {
        $ac = new \iPresso\Model\Action();
        $ac->setName('Unit tests');
        $ac->setKey('unit_tests');
        $response = $this->class->action->add($ac);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        return $ac->getKey();
    }

    /**
     * @depends testActionAdd
     * @param string $actionKey
     * @throws Exception
     * @return string
     */
    public function testActionEdit(string $actionKey)
    {
        $this->assertNotEmpty($actionKey);

        $action = new \iPresso\Model\Action();
        $action->setName('Unit tests edition');

        $response = $this->class->action->edit($actionKey, $action);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        $this->assertTrue($response->getData());

        return $actionKey;
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
     * @depends testActionAdd
     * @depends testContactAdd
     * @param string $actionKey
     * @param int $idContact
     * @throws Exception
     */
    public function testAddContactToAction(string $actionKey, int $idContact)
    {
        $this->assertNotEmpty($actionKey);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->action->addContact($actionKey, [$idContact]);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testContactAdd
     * @depends testActionAdd
     * @param int $idContact
     * @param string $actionKey
     * @return integer
     * @throws Exception
     */
    public function testContactAddAction(int $idContact, string $actionKey)
    {
        $this->assertGreaterThan(0, $idContact);
        $this->assertNotEmpty($actionKey);

        $action = new \iPresso\Model\ContactAction();
        $action->setKey($actionKey);

        $response = $this->class->contact->addAction($idContact, $action);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        return $actionKey;
    }

    /**
     * @depends testActionAdd
     * @param string $actionKey
     * @throws Exception
     */
    public function testActionDelete(string $actionKey)
    {
        $this->assertNotEmpty($actionKey);

        $response = $this->class->action->delete($actionKey);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}