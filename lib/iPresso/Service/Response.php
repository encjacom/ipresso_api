<?php

namespace iPresso\Service;

class Response
{
    /**
     * Content of demanded document (most often returned header in web communication)
     */
    const STATUS_OK = 200;

    /**
     * Created – sent document was saved on server
     */
    const STATUS_CREATED = 201;

    /**
     * No Content - The server successfully processed the request and is not returning any content.
     */
    const STATUS_NO_CONTENT = 204;

    /**
     * Multiple Choices - Indicates multiple options for the resource from which the client may choose.
     */
    const STATUS_MULTIPLE_CHOICES = 300;

    /**
     * Moved Permanently - This and all future requests should be directed to the given URI.
     */
    const STATUS_MOVED_PERMANENTLY = 301;

    /**
     * Found
     */
    const STATUS_FOUND = 302;

    /**
     * See Other - The response to the request can be found under another URI using a GET method.
     */
    const STATUS_SEE_OTHER = 303;

    /**
     * Not Modified - Indicates that the resource has not been modified since the version specified by the request headers If-Modified-Since or If-None-Match.
     */
    const STATUS_NOT_MODIFIED = 304;

    /**
     * Bad request – the request was invalid or cannot be otherwise served
     */
    const STATUS_BAD_REQUEST = 400;

    /**
     * Unauthorized - Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided.
     */
    const STATUS_UNAUTHORIZED = 401;

    /**
     * Forbidden – The request is understood, but access is not allowed.
     */
    const STATUS_FORBIDDEN = 403;

    /**
     * Not found – the URL requested is invalid or the resource requested, such as a user, does not exists
     */
    const STATUS_NOT_FOUND = 404;

    /**
     * Method Not Allowed - A request method is not supported for the requested resource; for example, a GET request on a form which requires data to be presented via POST, or a PUT request on a read-only resource.
     */
    const STATUS_METHOD_NOT_ALLOWED = 405;

    /**
     * Too Many Requests – the user has sent too many requests in a given amount of time.
     */
    const STATUS_TOO_MANY_REQUESTS = 429;

    /**
     * Internal Server Error – server encountered unexpected difficulties, which make execution of the request impossible
     */
    const STATUS_INTERNAL_SEVER_ERROR = 500;

    /**
     * Not implemented – server does not dispose of functionality required in the request; the server received unknown type of request
     */
    const STATUS_NOT_IMPLEMENTED = 501;

    /**
     * @var integer
     */
    public $code;

    /**
     * @var array
     */
    public $data;

    /**
     * @var integer
     */
    public $error_code;

    /**
     * @var string
     */
    public $error_message;

    /**
     * @var string
     */
    public $message;

    /**
     * Response constructor.
     * @param $response
     */
    public function __construct($response)
    {
        return $this->getResponse($response);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $response
     * @return Response
     */
    public function getResponse($response)
    {
        if (isset($response->code))
            $this->code = $response->code;

        if (isset($response->data))
            $this->data = $response->data;

        if (isset($response->errorCode))
            $this->error_code = $response->errorCode;

        if (isset($response->errorCode))
            $this->error_message = self::checkErrorCode($response->errorCode);

        if (isset($response->message))
            $this->message = $response->message;

        return $this;
    }

    /**
     * @param integer $code
     * @return bool|string
     */
    private static function checkErrorCode($code)
    {
        $error_codes = [
            1 => 'Contact already exists',
            2 => 'Exceeded limit',
            3 => 'Lack of contacts fulfilling criteria of the search',
            4 => 'Incorrect search criteria',
            5 => 'Error at the removal of the category of a contact',
            6 => 'Lack of category name',
            7 => 'Activity without defined key',
            8 => 'Activity without defined name',
            9 => 'Error at the addition of activity',
            10 => 'Lack of activity key',
            11 => 'Lack of contacts’ ID at function request',
            12 => 'Activity does not exist',
            13 => 'Contact does not exist',
            14 => 'Activity parameter does not exis',
            15 => 'Mass activity addition error',
            16 => 'Marketing agreement without name',
            17 => 'Error at agreement addition',
            18 => 'Error at agreement edition',
            19 => 'Error at agreement deletion',
            20 => 'Contact assignment to an agreement error',
            21 => 'Agreement does not exist',
            22 => 'Attribute without defined name',
            23 => 'Attribute without defined key',
            24 => 'Attribute without defined type',
            25 => 'Error at attribute addition',
            26 => 'Category does not exist',
            27 => 'Error at category addition',
            28 => 'Error at assigning contact to a category',
            29 => 'Category edition parameters error',
            30 => 'Lack of defined category ID',
            31 => 'Lack of defined tag name',
            32 => 'Lack of defined tag ID',
            33 => 'Error at tag addition',
            34 => 'Error at tag edition',
            35 => 'Error at tag deletion',
            36 => 'Error at tag assignment to a contact',
            37 => 'Tag does not exist',
            38 => 'Error of tag edition parameter',
            39 => 'Error at deletion, tag has a superior tag',
            40 => 'Lack of define type name',
            41 => 'Lack of defined type key',
            42 => 'Error of type addition',
            43 => 'Error of the assignment of type to a contact',
            44 => 'Incorrect contact ID',
            45 => 'Lack of contact ID',
            46 => 'Error during joining of contacts',
            47 => 'Error of the removal of connections of contacts',
            48 => 'Error at the addition of tags to a contact',
            49 => 'Incorrect data at the addition oftags to a contact',
            50 => 'Category addition error',
            51 => 'Incorrect data in relation to category addition',
            52 => 'Agreement assignment error',
            53 => 'Incorrect data in relation to contact agreement addition',
            54 => 'Contact deletion error',
            55 => 'Activity addition error',
            56 => 'Incorrect data in relation to activity addition',
            57 => 'Lack of data in relation to activity addition',
            58 => 'Contact type does not exit',
            59 => 'Contact edition error',
            60 => 'Incorrect contact data',
            61 => 'Tag deletion error',
            62 => 'Agreement deletion error',
            63 => 'Contact has integration',
            64 => 'Attribute with given key already exists',
            65 => 'Incorrect type of attribute',
            66 => 'Lack of URL address monitored website',
            67 => 'Incorrect URL address monitored website',
            68 => 'Monitored website addition error',
            69 => 'Incorrect monitored website ID',
            70 => 'The page number must be greater than 0',
            71 => 'The campaign is not called by the API',
            72 => 'Contact is not clear , at the specified e-mail address or phone number is more than 1 person',
            73 => 'Contacts does not have agreement',
            74 => 'The campaign is not yet ready',
            75 => 'E-mail address is not valid',
            76 => 'Contact does not have address e-mail',
            77 => 'Contact does not have phone number',
            78 => 'Incorrect contact hash',
            79 => 'Audience has more than one contact',
            87 => 'Agreement is used',
            89 => 'Lack of segmentation name',
            90 => 'Lack of segmentation live time',
            91 => 'Error at segmentation addition',
            92 => 'Segmentation is inactive',
            93 => 'Segmentation deletion error',
            94 => 'Segmentation does not exists',
            95 => 'Segmentation contact origin ID is wrong',
            96 => 'Segmentation was not created by API',
            97 => 'Voucher does not exists',
            98 => 'Contacts does not have web push token',
            99 => 'Scenario key not exists',
            100 => 'Campaign vendor missing',
            101 => 'Profile page missing',
            102 => 'Attribute wrong data',
            103 => 'Attribute option wrong data',
            104 => 'Attribute option exists',
            105 => 'Category exists',
            106 => 'External id not exists',
            107 => 'External id already exists',
            108 => 'External id group not exists',
            109 => 'Contact mobile number invalid',
            110 => 'File wrong data',
            111 => 'Vendor does not supports attachments',
            112 => 'IP address blocked',
            113 => 'User blocked',
            114 => 'Invalid js method api key',
        ];

        if (isset($error_codes[$code])) {
            return $error_codes[$code];
        }
        return false;
    }

}