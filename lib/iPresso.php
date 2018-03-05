<?php

use iPresso\Service\ActionService;
use iPresso\Service\ActivityService;
use iPresso\Service\AgreementService;
use iPresso\Service\AttributeService;
use iPresso\Service\CategoryService;
use iPresso\Service\CampaignService;
use iPresso\Service\ContactService;
use iPresso\Service\ScenarioService;
use iPresso\Service\SearchService;
use iPresso\Service\SegmentationService;
use iPresso\Service\SourceService;
use iPresso\Service\TagService;
use iPresso\Service\TypeService;
use iPresso\Service\WebsiteService;
use iPresso\Service\Service;
use Itav\Component\Serializer\Serializer;

/**
 * Class iPresso
 */
class iPresso
{
    /**
     * @var ActionService
     */
    public $action;

    /**
     * @var ActivityService
     */
    public $activity;

    /**
     * @var AgreementService
     */
    public $agreement;

    /**
     * @var AttributeService
     */
    public $attribute;

    /**
     * @var CampaignService
     */
    public $campaign;

    /**
     * @var CategoryService
     */
    public $category;

    /**
     * @var ContactService
     */
    public $contact;

    /**
     * @var ScenarioService
     */
    public $scenario;

    /**
     * @var SearchService
     */
    public $search;

    /**
     * @var SegmentationService
     */
    public $segmentation;

    /**
     * @var SourceService
     */
    public $source;

    /**
     * @var TagService
     */
    public $tag;

    /**
     * @var TypeService
     */
    public $type;

    /**
     * @var WebsiteService
     */
    public $www;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * iPresso constructor.
     */
    public function __construct()
    {
        $this->service = new Service();
        $this->serializer = new Serializer();
        $this->action = new ActionService($this->service, $this->serializer);
        $this->activity = new ActivityService($this->service, $this->serializer);
        $this->agreement = new AgreementService($this->service, $this->serializer);
        $this->attribute = new AttributeService($this->service, $this->serializer);
        $this->category = new CategoryService($this->service, $this->serializer);
        $this->campaign = new CampaignService($this->service, $this->serializer);
        $this->contact = new ContactService($this->service, $this->serializer);
        $this->scenario = new ScenarioService($this->service, $this->serializer);
        $this->search = new SearchService($this->service, $this->serializer);
        $this->segmentation = new SegmentationService($this->service, $this->serializer);
        $this->source = new SourceService($this->service, $this->serializer);
        $this->tag = new TagService($this->service, $this->serializer);
        $this->type = new TypeService($this->service, $this->serializer);
        $this->www = new WebsiteService($this->service, $this->serializer);
    }

    /**
     * @param mixed $customerKey
     * @return iPresso
     */
    public function setCustomerKey($customerKey)
    {
        $this->service->setCustomerKey($customerKey);
        return $this;
    }

    /**
     * @param mixed $login
     * @return iPresso
     */
    public function setLogin($login)
    {
        $this->service->setLogin($login);
        return $this;
    }

    /**
     * @param mixed $password
     * @return iPresso
     */
    public function setPassword($password)
    {
        $this->service->setPassword($password);
        return $this;
    }

    /**
     * @param mixed $token
     * @return iPresso
     */
    public function setToken($token)
    {
        $this->service->setToken($token);
        return $this;
    }

    /**
     * @param string $url
     * @return $this
     * @throws Exception
     */
    public function setUrl($url)
    {
        $address = parse_url($url);
        if (!isset($address['scheme']) || $address['scheme'] != 'https')
            throw new Exception('Set URL with https://');

        $this->service->setUrl($url);
        return $this;
    }

    /**
     * @param callable $callBack
     * @return iPresso
     */
    public function setTokenCallBack($callBack)
    {
        $this->service->setTokenCallBack($callBack);
        return $this;
    }

    /**
     * @return bool|\iPresso\Service\Response
     * @throws Exception
     */
    public function getToken()
    {
        return $this->service->getToken(true);
    }

    /**
     * @param $header
     * @return iPresso
     */
    public function addHeader($header)
    {
        $this->service->addCustomHeader($header);
        return $this;
    }

    /**
     * @return iPresso
     */
    public function debug()
    {
        $this->service->debug();
        return $this;
    }

    public static function dump($die, $variable, $desc = false, $noHtml = false)
    {
        if (is_string($variable)) {
            $variable = str_replace("<_new_line_>", "<BR>", $variable);
        }

        if ($noHtml) {
            echo "\n";
        } else {
            echo "<pre>";
        }

        if ($desc) {
            echo $desc . ": ";
        }

        print_r($variable);

        if ($noHtml) {
            echo "";
        } else {
            echo "</pre>";
        }

        if ($die) {
            die();
        }
    }

}