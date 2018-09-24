<?php

use PHPUnit\Framework\TestCase;

/**
 * Class OriginTest
 */
class ContactTest extends TestCase
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

    public function testContactClass()
    {
        $this->assertInstanceOf(\iPresso\Service\ContactService::class, $this->class->contact);
    }

    /**
     * @depends testContactClass
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
     * @depends testContactAdd
     * @param int $idContact
     * @return integer
     * @throws Exception
     */
    public function testContactGet(int $idContact)
    {
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->contact->get($idContact);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('contact', $response->getData());

        $this->assertEquals($idContact, $response->getData()->contact->idContact);

        return (integer)$idContact;
    }

    /**
     * @depends testContactGet
     * @param int $idContact
     * @throws Exception
     */
    public function testContactEdit(int $idContact)
    {
        $this->assertGreaterThan(0, $idContact);

        $contact = new \iPresso\Model\Contact();
        $contact->setFirstName('Michał');
        $contact->setLastName('Per');

        $response = $this->class->contact->edit($idContact, $contact);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

}