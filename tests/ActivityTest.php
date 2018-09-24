<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ActivityTest
 */
class ActivityTest extends TestCase
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
     * ActivityTest constructor.
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

    public function testActivityClass()
    {
        $this->assertInstanceOf(\iPresso\Service\ActivityService::class, $this->class->activity);
    }

    /**
     * @throws Exception
     */
    public function testActivityAddWrong()
    {
        $activity = new \iPresso\Model\Activity();

        $this->expectException(Exception::class);
        $activity->getActivity();
    }

    /**
     * @depends testActivityClass
     * @throws Exception
     */
    public function testActivityGetAll()
    {
        $response = $this->class->activity->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('activity', $response->getData());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function testActivityAdd()
    {
        $ac = new \iPresso\Model\Activity();
        $ac->setName('Unit tests');
        $ac->setKey('unit_tests');
        $response = $this->class->activity->add($ac);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        return $ac->getKey();
    }

    /**
     * @depends testActivityAdd
     * @param string $activityKey
     * @throws Exception
     * @return string
     */
    public function testActivityEdit(string $activityKey)
    {
        $this->assertNotEmpty($activityKey);

        $activity = new \iPresso\Model\Activity();
        $activity->setName('Unit tests edition');

        $response = $this->class->activity->edit($activityKey, $activity);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        $this->assertTrue($response->getData());

        return $activityKey;
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
     * @depends testActivityAdd
     * @depends testContactAdd
     * @param string $activityKey
     * @param int $idContact
     * @throws Exception
     */
    public function testAddContactToActivity(string $activityKey, int $idContact)
    {
        $this->assertNotEmpty($activityKey);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->activity->addContact($activityKey, [$idContact]);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testContactAdd
     * @depends testActivityAdd
     * @param int $idContact
     * @param string $activityKey
     * @return integer
     * @throws Exception
     */
    public function testContactAddActivity(int $idContact, string $activityKey)
    {
        $this->assertGreaterThan(0, $idContact);
        $this->assertNotEmpty($activityKey);

        $activity = new \iPresso\Model\ContactActivity();
        $activity->setKey($activityKey);

        $response = $this->class->contact->addActivity($idContact, $activity);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);

        return $activityKey;
    }

    /**
     * @depends testActivityAdd
     * @param string $activityKey
     * @throws Exception
     */
    public function testActivityDelete(string $activityKey)
    {
        $this->assertNotEmpty($activityKey);

        $response = $this->class->activity->delete($activityKey);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}