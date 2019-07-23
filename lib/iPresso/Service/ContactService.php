<?php

namespace iPresso\Service;

use iPresso\Model\Contact;
use iPresso\Model\ContactAction;
use iPresso\Model\ContactActivity;
use iPresso\Model\MassContactAction;
use iPresso\Model\MassContactActivity;
use Itav\Component\Serializer\Serializer;

/**
 * Class ContactService
 * @package iPresso\Service
 */
class ContactService implements ServiceInterface
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * ContactService constructor.
     * @param Service $service
     * @param Serializer $serializer
     */
    public function __construct(Service $service, Serializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * Adding new contact
     * @see http://apidoc.ipresso.pl/v2/en/#adding-new-contact
     * @param Contact|Contact[] $contact
     * @return bool|Response
     * @throws \Exception
     */
    public function add($contact)
    {
        $post_data = [];

        if (is_array($contact)) {
            foreach ($contact as $c)
                $post_data['contact'][] = $c->getContact();
        } else {
            $post_data['contact'][] = $contact->getContact();
        }

        return $this
            ->service
            ->setRequestPath('contact')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($post_data)
            ->request();
    }

    /**
     * Edition of a contact with a given ID number
     * @see http://apidoc.ipresso.pl/v2/en/#edition-of-a-contact-with-a-given-id-number
     * @param string $idContact
     * @param Contact $contact
     * @return bool|Response
     * @throws \Exception
     */
    public function edit($idContact, Contact $contact)
    {
        if (!$idContact)
            $idContact = $contact->getIdContact();

        if (!$idContact)
            throw new \Exception('Contact id missing.');

        return $this
            ->service
            ->setRequestPath('contact/' . $idContact)
            ->setRequestType(Service::REQUEST_METHOD_PUT)
            ->setPostData(['contact' => $contact->getContact()])
            ->request();
    }

    /**
     * Delete contact
     * @see http://apidoc.ipresso.pl/v2/en/#delete-contact
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function delete($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Collect contact’s data with a given ID number
     * @see http://apidoc.ipresso.pl/v2/en/#collect-contact-39-s-data-with-a-given-id-number
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function get($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Adding tags to contacts with a given ID
     * @see http://apidoc.ipresso.pl/v2/en/#adding-tags-to-contacts-with-a-given-id
     * @param string $idContact
     * @param string $tagString
     * @return bool|Response
     * @throws \Exception
     */
    public function addTag($idContact, $tagString)
    {
        $data['tag'] = [$tagString];
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/tag')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Collecting tags for a contact
     * @see http://apidoc.ipresso.pl/v2/en/#collecting-tags-for-a-contact
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getTag($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/tag')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Deleting contact’s tag
     * @see http://apidoc.ipresso.pl/v2/en/#deleting-contact-39-s-tag
     * @param string $idContact
     * @param integer $idTag
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteTag($idContact, $idTag)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/tag/' . $idTag)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Adding category to a contact
     * @see http://apidoc.ipresso.pl/v2/en/#adding-category-to-a-contact
     * @param string $idContact
     * @param array $categoryIds
     * @return bool|Response
     * @throws \Exception
     */
    public function addCategory($idContact, $categoryIds)
    {
        $data['category'] = $categoryIds;
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/category')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get category for a contact
     * @see http://apidoc.ipresso.pl/v2/en/#get-category-for-a-contact
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getCategory($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/category')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Deleting contact’s category
     * @see http://apidoc.ipresso.pl/v2/en/#deleting-contact-39-s-category
     * @param string $idContact
     * @param integer $idCategory
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteCategory($idContact, $idCategory)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/category/' . $idCategory)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Get integration of the contact
     * @see http://apidoc.ipresso.pl/v2/en/#get-integration-of-the-contact
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getIntegration($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/integration')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Adding agreements to contacts
     * Parameter `ID_AGREEMENT` should be replaced with ID number of the agreement.
     * Then add status of the agreement. In the case of acceptance the status equals 1.
     * Parameter `ID_AGREEMENT_STATUS` is agreement status, in the case of activation of agreement enter number 1, in other cases enter 2.
     * @see http://apidoc.ipresso.pl/v2/en/#adding-agreements-to-contacts
     * @param string $idContact
     * @param array $agreement [ID_AGREEMENT => ID_AGREEMENT_STATUS]
     * @return bool|Response
     * @throws \Exception
     */
    public function addAgreement($idContact, $agreement)
    {
        $data['agreement'] = $agreement;
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/agreement')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get contact’s agreements
     * @see http://apidoc.ipresso.pl/v2/en/#get-contact-39-s-agreements
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getAgreement($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/agreement')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Delete agreement of a given ID from a contact of a given ID
     * @see http://apidoc.ipresso.pl/v2/en/#delete-agreement-of-a-given-id-from-a-contact-of-a-given-id
     * @param string $idContact
     * @param integer $idAgreement
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteAgreement($idContact, $idAgreement)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/agreement/' . $idAgreement)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Adding activity to a contact
     * @see http://apidoc.ipresso.pl/v2/en/#adding-activity-to-a-contact
     * @param string $idContact
     * @param ContactActivity $contactActivity
     * @return bool|Response
     * @throws \Exception
     */
    public function addActivity($idContact, ContactActivity $contactActivity)
    {
        $data['activity'][] = $contactActivity->getContactActivity();
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/activity')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Adding activities to a contact
     * @see http://apidoc.ipresso.pl/v2/en/#adding-activity-to-a-contact
     * @param string $idContact
     * @param ContactActivity[] $contactActivities
     * @return bool|Response
     * @throws \Exception
     */
    public function addActivities($idContact, $contactActivities)
    {
        $data = [];
        foreach ($contactActivities as $contactActivity) {
            if ($contactActivity instanceof ContactActivity)
                $data['activity'][] = $contactActivity->getContactActivity();
        }

        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/activity')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get available activities
     * @see http://apidoc.ipresso.pl/v2/en/#get-available-activities
     * @param string $idContact
     * @param integer|bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getActivity($idContact, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/activity' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Add actions to contact
     * @see http://apidoc.ipresso.pl/v2/en/#add-actions-to-a-contact
     * @param string $idContact
     * @param ContactAction $contactAction
     * @return bool|Response
     * @throws \Exception
     */
    public function addAction($idContact, ContactAction $contactAction)
    {
        $data['action'][] = $contactAction->getContactAction();
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/action')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get available actions
     * @see http://apidoc.ipresso.pl/v2/en/#get-actions-of-a-contact
     * @param string $idContact
     * @param integer|bool $page
     * @return bool|Response
     * @throws \Exception
     */
    public function getAction($idContact, $page = false)
    {
        if ($page && is_numeric($page))
            $page = '?page=' . $page;

        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/action' . $page)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Get contact type
     * @see http://apidoc.ipresso.pl/v2/en/#get-contact-types
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getType($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/type')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Setting contact type
     * @see http://apidoc.ipresso.pl/v2/en/#setting-contact-types
     * @param string $idContact
     * @param string $typeKey
     * @return bool|Response
     * @throws \Exception
     */
    public function setType($idContact, $typeKey)
    {
        $data['type'] = $typeKey;
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/type')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Get connections between contacts
     * @see http://apidoc.ipresso.pl/v2/en/#get-connections-between-contacts
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getConnection($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/connection')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Connect contacts
     * @see http://apidoc.ipresso.pl/v2/en/#connect-contacts
     * @param string $idContact
     * @param string $idContactToConnect
     * @return bool|Response
     * @throws \Exception
     */
    public function setConnection($idContact, $idContactToConnect)
    {
        $data['connection'] = [$idContactToConnect];
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/connection')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * @param integer $idContact
     * @param integer $idChild
     * @return bool|Response
     * @throws \Exception
     */
    public function deleteConnection($idContact, $idChild)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/connection/' . $idChild)
            ->setRequestType(Service::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Mass addition of activities to contacts
     * @see http://apidoc.ipresso.pl/v2/en/#mass-addition-of-activities-to-contacts
     * @param MassContactActivity $massContactActivity
     * @return bool|Response
     * @throws \Exception
     */
    public function addMassActivity(MassContactActivity $massContactActivity)
    {
        $data['contact'] = $this->serializer->normalize($massContactActivity->getContactActivities());
        return $this
            ->service
            ->setRequestPath('contact/activity')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * Mass addition of actions to contacts
     * @see http://apidoc.ipresso.pl/v2/en/#mass-addition-of-actions-to-contacts
     * @param MassContactAction $massContactAction
     * @return bool|Response
     * @throws \Exception
     */
    public function addMassAction(MassContactAction $massContactAction)
    {
        $data['contact'] = $this->serializer->normalize($massContactAction);
        return $this
            ->service
            ->setRequestPath('contact/action')
            ->setRequestType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->request();
    }

    /**
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getProfilePage($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/profilepage')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Get connections between contacts
     * @param string $idContact
     * @return bool|Response
     * @throws \Exception
     */
    public function getConnectionExtended($idContact)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/connection/extended')
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Get contact’s agreements that are assigned to group
     * @param string $idContact
     * @param $groupApiKey
     * @return bool|Response
     * @throws \Exception
     */
    public function getAgreementsByGroup($idContact, $groupApiKey)
    {
        return $this
            ->service
            ->setRequestPath('contact/' . $idContact . '/agreement/group/'.$groupApiKey)
            ->setRequestType(Service::REQUEST_METHOD_GET)
            ->request();
    }
}