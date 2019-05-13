<?php

namespace iPresso\Model;

class Campaign
{
    const VAR_EMAIL = 'email';
    const VAR_ID_CONTACT = 'contactId';
    const VAR_MOBILE = 'phone';
    const VAR_CONTENT = 'content';
    const VAR_HASH = 'hash';
    const VAR_TOKEN = 'token';
    const VAR_FILES = 'files';

    public $campaign = [];
    /**
     * @var array
     */
    private $content = [];
    /**
     * @var array
     */
    private $email = [];
    /**
     * @var array
     */
    private $id_contact = [];
    /**
     * @var array
     */
    private $mobile = [];
    /**
     * @var array
     */
    private $hash = [];
    /**
     * @var array
     */
    private $token = [];
    /**
     * @var array
     */
    private $files = [];

    /**
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param array $email
     * @return Campaign
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return Campaign
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return array
     */
    public function getIdContact()
    {
        return $this->id_contact;
    }

    /**
     * @param array $id_contact
     * @return Campaign
     */
    public function setIdContact($id_contact)
    {
        $this->id_contact = $id_contact;
        return $this;
    }

    /**
     * @return array
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param array $mobile
     * @return Campaign
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @param string $bracket
     * @param string $value
     * @return Campaign
     */
    public function addContent($bracket, $value)
    {
        $this->content[$bracket] = $value;
        return $this;
    }

    /**
     * @param $email
     * @return Campaign
     */
    public function addEmail($email)
    {
        $this->email[] = $email;
        return $this;
    }

    /**
     * @param $mobile
     * @return Campaign
     */
    public function addMobile($mobile)
    {
        $this->mobile[] = $mobile;
        return $this;
    }

    /**
     * @param $idContact
     * @return Campaign
     */
    public function addIdContact($idContact)
    {
        $this->id_contact[] = $idContact;
        return $this;
    }

    /**
     * @return array
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param array $hash
     * @return Campaign
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @param $hash
     * @return Campaign
     */
    public function addHash($hash)
    {
        $this->hash[] = $hash;
        return $this;
    }

    /**
     * @return array
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param array $token
     * @return Campaign
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param string $token
     * @return Campaign
     */
    public function addToken($token)
    {
        $this->token[] = $token;
        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     * @return Campaign
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @param array $file
     * @return Campaign
     */
    public function addFile($file)
    {
        $this->files[] = $file;
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getCampaign()
    {
        if (!empty($this->content))
            $this->campaign[self::VAR_CONTENT] = $this->content;

        if (!empty($this->email))
            $this->campaign[self::VAR_EMAIL] = $this->email;

        if (!empty($this->id_contact))
            $this->campaign[self::VAR_ID_CONTACT] = $this->id_contact;

        if (!empty($this->mobile))
            $this->campaign[self::VAR_MOBILE] = $this->mobile;

        if (!empty($this->hash))
            $this->campaign[self::VAR_HASH] = $this->hash;

        if (!empty($this->token))
            $this->campaign[self::VAR_TOKEN] = $this->token;

        if (!empty($this->files))
            $this->campaign[self::VAR_FILES] = $this->files;

        if (empty($this->campaign))
            throw new \Exception('No recipients in campaign');

        return $this->campaign;
    }
}
