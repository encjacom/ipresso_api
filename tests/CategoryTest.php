<?php

use PHPUnit\Framework\TestCase;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase
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
     * CategoryTest constructor.
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

    public function testCategoryClass()
    {
        $this->assertInstanceOf(\iPresso\Service\CategoryService::class, $this->class->category);
    }

    /**
     * @throws Exception
     */
    public function testCategoryAddWrong()
    {
        $tag = new \iPresso\Model\Category();

        $this->expectException(Exception::class);
        $tag->getCategory();
    }

    /**
     * @depends testCategoryClass
     */
    public function testCategoryGetAll()
    {
        $response = $this->class->category->get();

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertEquals(\iPresso\Service\Response::STATUS_OK, $response->getCode());

        $this->assertObjectHasAttribute('category', $response->getData());
    }

    /**
     * @return integer
     * @throws Exception
     */
    public function testCategoryAdd()
    {
        $category = new \iPresso\Model\Category();
        $category->setName('Unit tests');
        $response = $this->class->category->add($category);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED, \iPresso\Service\Response::STATUS_FOUND]);

        $this->assertObjectHasAttribute('category', $response->getData());

        $this->assertGreaterThan(0, $response->getData()->category->id);

        return (integer)$response->getData()->category->id;
    }

    /**
     * @depends testCategoryAdd
     * @param int $idCategory
     * @return int
     */
    public function testCategoryGet(int $idCategory)
    {
        $this->assertGreaterThan(0, $idCategory);

        $response = $this->class->category->get($idCategory);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('category', $response->getData());

        return $idCategory;
    }

    /**
     * @depends testCategoryGet
     * @param int $idCategory
     * @throws Exception
     * @return int
     */
    public function testCategoryEdit(int $idCategory)
    {
        $this->assertGreaterThan(0, $idCategory);

        $category = new \iPresso\Model\Category();
        $category->setName('Unit tests edition');

        $response = $this->class->category->edit($idCategory, $category);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertTrue($response->getData());

        return $idCategory;
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
     * @depends testCategoryAdd
     * @depends testContactAdd
     * @param int $idCategory
     * @param int $idContact
     * @throws Exception
     */
    public function testAddContactToCategory(int $idCategory, int $idContact)
    {
        $this->assertGreaterThan(0, $idCategory);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->category->addContact($idCategory, [$idContact]);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_CREATED]);
    }

    /**
     * @depends testCategoryAdd
     * @depends testContactAdd
     * @depends testAddContactToCategory
     * @param int $idCategory
     * @param int $idContact
     * @throws Exception
     */
    public function testGetContactCategory(int $idCategory, int $idContact)
    {
        $this->assertGreaterThan(0, $idCategory);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->category->getContact($idCategory);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('id', $response->getData());

        $this->assertContains($idContact, $response->getData()->id);
    }

    /**
     * @depends testCategoryAdd
     * @depends testContactAdd
     * @depends testAddContactToCategory
     * @param int $idCategory
     * @param int $idContact
     */
    public function testDeleteContactCategory(int $idCategory, int $idContact)
    {
        $this->assertGreaterThan(0, $idCategory);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->category->deleteContact($idCategory, $idContact);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }

    /**
     * @depends testCategoryAdd
     * @depends testContactAdd
     * @depends testDeleteContactCategory
     * @param int $idCategory
     * @param int $idContact
     */
    public function testCheckContactHasCategoryAfterDelete(int $idCategory, int $idContact)
    {
        $this->assertGreaterThan(0, $idCategory);
        $this->assertGreaterThan(0, $idContact);

        $response = $this->class->category->getContact($idCategory);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);

        $this->assertObjectHasAttribute('count', $response->getData());

        if ($response->getData->count > 0) {
            $this->assertObjectHasAttribute('id', $response->getData());

            $this->assertNotContains($idContact, $response->getData()->id);
        }
    }

    /**
     * @depends testCategoryAdd
     * @param int $idCategory
     */
    public function testCategoryDelete(int $idCategory)
    {
        $this->assertGreaterThan(0, $idCategory);

        $response = $this->class->category->delete($idCategory);

        $this->assertInstanceOf(\iPresso\Service\Response::class, $response);

        $this->assertContains($response->getCode(), [\iPresso\Service\Response::STATUS_OK]);
    }
}