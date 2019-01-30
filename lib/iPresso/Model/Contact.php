<?php

namespace iPresso\Model;

class Contact
{
    const REGION = 'region';
    const VAR_AGREEMENT = 'agreement';
    const VAR_BUILDING_NUMBER = 'buildingNumber';
    const VAR_CATEGORY = 'category';
    const VAR_CITY = 'city';
    const VAR_COMPANY = 'company';
    const VAR_COUNTRY = 'country';
    const VAR_EMAIL = 'email';
    const VAR_FIRST_NAME = 'fname';
    const VAR_FLAT_NUMBER = 'flatNumber';
    const VAR_ID_EXTERNAL = 'idExternal';
    const VAR_IP = 'ip';
    const VAR_LAST_NAME = 'lname';
    const VAR_MOBILE = 'mobile';
    const VAR_NAME = 'name';
    const VAR_ORIGIN = 'origin';
    const VAR_PHONE = 'phone';
    const VAR_POST_CODE = 'postCode';
    const VAR_STREET = 'street';
    const VAR_TAG = 'tag';
    const VAR_TYPE = 'type';
    const VAR_WORK_POSITION = 'workPosition';
    const VAR_WWW = 'www';

    /**
     * iPresso API null variable
     * @var string
     */
    public static $null = 'API_NULL';

    /**
     * @var array
     */
    private $attribute = [];

    /**
     * Building number
     * @var string
     */
    private $building_number = '';

    /**
     * City
     * @var string
     */
    private $city = '';

    /**
     * Name of company
     * @var string
     */
    private $company = '';

    /**
     * Country
     * @var string
     */
    private $country = '';

    /**
     * E-mail address
     * @required *
     * @var string
     */
    private $email = '';

    /**
     * Flat number
     * @var string
     */
    private $flat_number = '';

    /**
     * Region
     * @var string
     */
    private $region = '';


    /**
     * First name
     * @var string
     */
    private $first_name = '';

    /**
     * @var integer
     */
    private $id_contact;

    /**
     * Last name
     * @required *
     * @var string
     */
    private $last_name = '';

    /**
     * Mobile phone number
     * @required *
     * @var string
     */
    private $mobile = '';

    /**
     * Own name
     * @required *
     * @var string
     */
    private $name = '';

    /**
     * Landline phone number
     * @var string
     */
    private $phone = '';

    /**
     * Post code
     * @var string
     */
    private $post_code = '';

    /**
     * Street
     * @var string
     */
    private $street = '';

    /**
     * Key of contact’s type
     * @var string
     */
    private $type = '';

    /**
     * Associative array with a pair - agreement IDs => agreement status, where 1 = add agreement, 2 = delete agreement
     * @var array
     */
    private $agreement = [];

    /**
     * Associative array with a pair - category ID => category status, where 1 = add category, 2 = delete category
     * @var array
     */
    private $category = [];

    /**
     * One-dimensional array containing tag
     * @var array
     */
    private $tag = [];

    /**
     * @var string
     */
    private $origin;

    /**
     * IP Address
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $www;

    /**
     * @var string
     */
    private $work_position;

    /**
     * @var string
     */
    private $modify_date;

    /**
     * @var string
     */
    private $id_external;

    /**
     * @param int $id_contact
     * @return Contact
     */
    public function setIdContact($id_contact)
    {
        $this->id_contact = $id_contact;
        return $this;
    }

    /**
     * @param string $building_number
     * @return Contact
     */
    public function setBuildingNumber($building_number)
    {
        $this->building_number = $building_number;
        return $this;
    }

    /**
     * @param string $city
     * @return Contact
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $company
     * @return Contact
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param string $country
     * @return Contact
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param $email
     * @return Contact
     * @throws \Exception
     */
    public function setEmail($email)
    {
        if ($email !== self::$null) {
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
                throw new \Exception('Wrong email address');
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $flat_number
     * @return Contact
     */
    public function setFlatNumber($flat_number)
    {
        $this->flat_number = $flat_number;
        return $this;
    }

    /**
     * @param string $first_name
     * @return Contact
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param string $last_name
     * @return Contact
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param string $mobile
     * @return Contact
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $post_code
     * @return Contact
     */
    public function setPostCode($post_code)
    {
        $this->post_code = $post_code;
        return $this;
    }

    /**
     * @param string $street
     * @return Contact
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param string $type
     * @return Contact
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array $agreement
     * @return Contact
     */
    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
        return $this;
    }

    /**
     * @param integer $id_agreement
     * @param integer $status
     * @return Contact
     */
    public function addAgreement($id_agreement, $status)
    {
        $this->agreement[$id_agreement] = $status;
        return $this;
    }

    /**
     * @param array $category
     * @return Contact
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param integer $id_category
     * @param integer $status
     * @return $this
     */
    public function addCategory($id_category, $status)
    {
        $this->category[$id_category] = $status;
        return $this;
    }

    /**
     * @param array $tag
     * @return Contact
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @param string $tag
     * @return Contact
     */
    public function addTag($tag)
    {
        $this->tag[] = $tag;
        return $this;
    }

    /**
     * Set variable to NULL
     * @param $variable
     */
    public function setNull($variable)
    {
        if (isset($this->{$variable}))
            $this->{$variable} = self::$null;
    }

    /**
     * @param string $apiKey
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($apiKey, $value)
    {
        $this->attribute[$apiKey] = $value;
        return $this;
    }

    /**
     * @param string $apiKey
     * @return mixed|false
     */
    public function getAttribute($apiKey)
    {
        if (!isset($this->attribute[$apiKey])) {
            return false;
        }
        return $this->attribute[$apiKey];
    }

    /**
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->building_number;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFlatNumber()
    {
        return $this->flat_number;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return int
     */
    public function getIdContact()
    {
        return $this->id_contact;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->post_code;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * @return array
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return array
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Contact
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return Contact
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param string $www
     * @return Contact
     */
    public function setWww($www)
    {
        $this->www = $www;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorkPosition()
    {
        return $this->work_position;
    }

    /**
     * @param string $work_position
     * @return Contact
     */
    public function setWorkPosition($work_position)
    {
        $this->work_position = $work_position;
        return $this;
    }

    /**
     * @return string
     */
    public function getModifyDate()
    {
        return $this->modify_date;
    }

    /**
     * @param string $modify_date
     * @return Contact
     */
    public function setModifyDate($modify_date)
    {
        $this->modify_date = $modify_date;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdExternal()
    {
        return $this->id_external;
    }

    /**
     * @param string $id_external
     * @return Contact
     */
    public function setIdExternal($id_external)
    {
        $this->id_external = $id_external;
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getContact()
    {
        $contact = [];

        if (!empty($this->type))
            $contact[self::VAR_TYPE] = $this->type;

        if (!empty($this->first_name))
            $contact[self::VAR_FIRST_NAME] = $this->first_name;

        if (!empty($this->last_name))
            $contact[self::VAR_LAST_NAME] = $this->last_name;

        if (!empty($this->name))
            $contact[self::VAR_NAME] = $this->name;

        if (!empty($this->email))
            $contact[self::VAR_EMAIL] = $this->email;

        if (!empty($this->phone))
            $contact[self::VAR_PHONE] = $this->phone;

        if (!empty($this->mobile))
            $contact[self::VAR_MOBILE] = $this->mobile;

        if (!empty($this->company))
            $contact[self::VAR_COMPANY] = $this->company;

        if (!empty($this->city))
            $contact[self::VAR_CITY] = $this->city;

        if (!empty($this->post_code))
            $contact[self::VAR_POST_CODE] = $this->post_code;

        if (!empty($this->flat_number))
            $contact[self::VAR_FLAT_NUMBER] = $this->flat_number;

        if (!empty($this->building_number))
            $contact[self::VAR_BUILDING_NUMBER] = $this->building_number;

        if (!empty($this->region))
            $contact[self::REGION] = $this->region;

        if (!empty($this->country))
            $contact[self::VAR_COUNTRY] = $this->country;

        if (!empty($this->category))
            $contact[self::VAR_CATEGORY] = $this->category;

        if (!empty($this->tag))
            $contact[self::VAR_TAG] = $this->tag;

        if (!empty($this->agreement))
            $contact[self::VAR_AGREEMENT] = $this->agreement;

        if (!empty($this->origin))
            $contact[self::VAR_ORIGIN] = $this->origin;

        if (!empty($this->ip))
            $contact[self::VAR_IP] = $this->ip;

        if (!empty($this->www))
            $contact[self::VAR_WWW] = $this->www;

        if (!empty($this->street))
            $contact[self::VAR_STREET] = $this->street;

        if (!empty($this->work_position))
            $contact[self::VAR_WORK_POSITION] = $this->work_position;

        if (!empty($this->id_external))
            $contact[self::VAR_ID_EXTERNAL] = $this->id_external;

        if (!empty($this->attribute)) {
            foreach ($this->attribute as $apiKey => $value) {
                $contact[$apiKey] = $value;
            }
        }

        return $contact;
    }

}