<?php

use PHPUnit\Framework\TestCase;

/**
 * Class AgreementTest
 */
class AgreementTest extends TestCase
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
     * AgreementTest constructor.
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

    public function testAgreementClass()
    {
        $this->assertInstanceOf(\iPresso\Service\AgreementService::class, $this->class->agreement);
    }

    /**
     * @depends testAgreementClass
     * @see http://apidoc.ipresso.pl/v2/en/#get-all-available-agreements
     * @throws Exception
     */
    public function testAgreementGetAll()
    {
        $response = $this->class->agreement->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('agreement', $response->getData());
    }

    /**
     * @throws Exception
     */
    public function testAgreementAddWrong()
    {
        $agreement = new \iPresso\Model\Agreement();

        $this->expectException(Exception::class);
        $agreement->getAgreement();
    }

    /**
     * @return integer
     * @throws Exception
     */
    public function testAgreementAdd()
    {
        $agreement = new \iPresso\Model\Agreement();
        $agreement->setName('Unit tests');
        $agreement->setDescription('Unit tests description.');
        $agreement->setDmStatus(\iPresso\Model\Agreement::DIRECT_MARKETING_VISIBLE);
        $response = $this->class->agreement->add($agreement);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('agreement', $response->getData());

        $this->assertGreaterThan(0, $response->getData()->agreement->id);

        return (integer)$response->getData()->agreement->id;
    }

    /**
     * @depends testAgreementAdd
     * @param integer $idAgreement
     * @return integer
     * @throws Exception
     */
    public function testAgreementEdit($idAgreement)
    {
        $this->assertGreaterThan(0, $idAgreement);

        $agreement = new \iPresso\Model\Agreement();
        $agreement->setName('Unit tests edition');
        $agreement->setDescription('Unit tests edition.');

        $response = $this->class->agreement->edit($idAgreement, $agreement);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        return $idAgreement;
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

        $this->assertContains($contact->code, [\iPresso\Service\Response::STATUS_OK, \iPresso\Service\Response::STATUS_FOUND, \iPresso\Service\Response::STATUS_SEE_OTHER]);

        $this->assertGreaterThan(0, $contact->id);

        return (integer)$contact->id;
    }

    /**
     * @depends testAgreementAdd
     * @depends testContactAdd
     * @param int $idAgreement
     * @param int $idContact
     * @throws Exception
     */
    public function testAddContactToAgreement(int $idAgreement, int $idContact)
    {
        $this->assertGreaterThan(0, $idAgreement);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->agreement->addContact($idAgreement, [$idContact]);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testAgreementAdd
     * @depends testContactAdd
     * @depends testAddContactToAgreement
     * @param int $idAgreement
     * @param int $idContact
     * @throws Exception
     */
    public function testGetContactAgreement(int $idAgreement, int $idContact)
    {
        $this->assertGreaterThan(0, $idAgreement);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->agreement->getContact($idAgreement);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('id', $response->getData());

        $this->assertContains($idContact, $response->getData()->id);
    }

    /**
     * @depends testAgreementAdd
     * @depends testContactAdd
     * @depends testAddContactToAgreement
     * @param int $idAgreement
     * @param int $idContact
     * @throws Exception
     */
    public function testDeleteContactAgreement(int $idAgreement, int $idContact)
    {
        $this->assertGreaterThan(0, $idAgreement);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->agreement->deleteContact($idAgreement, $idContact);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }

    /**
     * @depends testAgreementAdd
     * @depends testContactAdd
     * @depends testDeleteContactAgreement
     * @param int $idAgreement
     * @param int $idContact
     * @throws Exception
     */
    public function testCheckContactHasAgreementAfterDelete(int $idAgreement, int $idContact)
    {
        $this->assertGreaterThan(0, $idAgreement);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->agreement->getContact($idAgreement);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('count', $response->getData());

        if ($response->getData->count > 0) {
            $this->assertObjectHasAttribute('id', $response->getData());

            $this->assertNotContains($idContact, $response->getData()->id);
        }
    }

    /**
     * @depends testAgreementAdd
     * @param int $idAgreement
     * @throws Exception
     */
    public function testAgreementDelete(int $idAgreement)
    {
        $this->assertGreaterThan(0, $idAgreement);

        $response = $this->class->agreement->delete($idAgreement);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}