<?php

use PHPUnit\Framework\TestCase;

/**
 * Class SegmentationTest
 */
class SegmentationTest extends TestCase
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
     * SegmentationTest constructor.
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

    public function testSegmentationClass()
    {
        $this->assertInstanceOf(\iPresso\Service\SegmentationService::class, $this->class->segmentation);
    }

    /**
     * @throws Exception
     */
    public function testSegmentationAddWrong()
    {
        $type = new \iPresso\Model\Segmentation();

        $this->expectException(Exception::class);
        $type->getSegmentation();
    }

    /**
     * @depends testSegmentationClass
     * @throws Exception
     */
    public function testSegmentationGetAll()
    {
        $response = $this->class->segmentation->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('segmentation', $response->getData());
    }

    /**
     * @return integer
     * @throws Exception
     */
    public function testSegmentationAdd()
    {
        $type = new \iPresso\Model\Segmentation();
        $type->setName('Unit tests');
        $type->setLiveTime(1);
        $response = $this->class->segmentation->add($type);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        $this->assertObjectHasAttribute('id', $response->getData());

        return (integer)$response->getData()->id;
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
     * @depends testSegmentationAdd
     * @depends testContactAdd
     * @param integer $idSegmentation
     * @param int $idContact
     * @throws Exception
     */
    public function testAddContactToSegmentation(int $idSegmentation, int $idContact)
    {
        $this->assertGreaterThan(0, $idSegmentation);
        $this->assertGreaterThan(0, $idContact);

        $segmentation = new \iPresso\Model\Segmentation();
        $segmentation->addContact($idContact);
        $segmentation->setContactOrigin(\iPresso\Model\Segmentation::CONTACT_ORIGIN_ID);

        $response = $this->class->segmentation->addContact($idSegmentation, $segmentation);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testSegmentationAdd
     * @depends testContactAdd
     * @param int $idSegmentation
     * @param int $idContact
     * @throws Exception
     */
    public function testGetContactInSegmentation(int $idSegmentation, int $idContact)
    {
        $this->assertGreaterThan(0, $idSegmentation);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->segmentation->getContact($idSegmentation);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('contact', $response->getData());

        $this->assertNotEmpty($response->getData()->contact->$idContact);
    }

    /**
     * @depends testSegmentationAdd
     * @param int $idSegmentation
     * @throws Exception
     */
    public function testDeleteSegmentation(int $idSegmentation)
    {
        $this->assertGreaterThan(0, $idSegmentation);

        $response = $this->class->segmentation->delete($idSegmentation);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}