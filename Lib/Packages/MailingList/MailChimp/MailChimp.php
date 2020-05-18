<?php

namespace Twork\Packages\MailingList\MailChimp;

use Twork\Exceptions\RequiredPropertyException;
use Twork\Packages\Http\Curl;
use Twork\Packages\Http\Response;

/**
 * Class MailChimp
 * @package Twork\Packages\MailingList\MailChimp
 */
class MailChimp
{
    /**
     * @var string The MailChimp API URL.
     */
    protected $endpoint = 'https://<dc>.api.mailchimp.com/3.0/';

    /**
     * @var string Your API Key.
     */
    protected $apiKey;

    /**
     * @var array Headers.
     */
    protected $headers;

    /**
     * @var string The URL.
     */
    protected $url;

    /**
     * @var bool|string The cURL request.
     */
    protected $request;

    /**
     * @var Response The cURL request response.
     */
    protected $response;

    /**
     * @var array MailChimp API errors.
     */
    protected $errors = [];

    /**
     * @var string The current request type.
     */
    protected $currentRequestType;

    /**
     * @var array Contact details displayed in campaign footers.
     */
    protected $contact;

    /**
     * @var array Default values for campaigns created.
     */
    protected $campaignDefaults;

    /**
     * MailChimp constructor.
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        [, $dataCenter] = explode('-', $this->apiKey);

        $this->endpoint = str_replace('<dc>', $dataCenter, $this->endpoint);

        $this->headers = [
            'Authorization: apikey ' . $this->apiKey
        ];
    }

    /**
     * Make a get request.
     *
     * @param string $url
     *
     * @param array  $params
     *
     * @return MailChimp
     */
    public function get(string $url, $params = []): MailChimp
    {
        $this->sendRequest('get', $url, $params);

        return $this;
    }

    /**
     * Make a post request.
     *
     * @param string $url
     * @param array  $data
     *
     * @return MailChimp
     */
    public function post(string $url, array $data): MailChimp
    {
        $this->sendRequest('post', $url, $data);

        return $this;
    }

    /**
     * Send the request.
     *
     * @param $method
     * @param $url
     * @param $args
     *
     * @return void
     */
    public function sendRequest($method, $url, $args): void
    {
        $this->url = $this->endpoint . $url;

        $curl = new Curl($this->url, $this->headers);

        $response = $curl->{$method}($args)->getResponse();

        $error = new MailChimpError();
        $error->route = $response->headers['url'];
        $error->status = $response->status;
        $error->details = $response->error;

        if ($response->error) {
            $this->errors['error'][] = $error;
        } elseif ($response->status === 403) {
            $this->errors['403'][] = $error;
        } elseif ($response->status === 404) {
            $this->errors['404'][] = $error;
        }

        $this->response = $response;

        if ($response->body) {
            $this->response->body = json_decode($response->body);
        }

        if (!isset($error->details) && isset($response->body->detail)) {
            $error->details = $response->body->detail;
        }

        $this->addErrorHelpers();
    }

    /**
     * Get the MailChimp API response.
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Get any MailChimp API errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return true if there were no errors.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return empty($this->errors);
    }

    /**
     * Returns true if the request did not cause a true error.
     *
     * @return bool
     */
    public function requestWasValid(): bool
    {
        return empty($this->errors['cURL Error']);
    }

    /**
     * Add helpful messages to some vague errors.
     */
    protected function addErrorHelpers(): void
    {
        $type = $this->currentRequestType;

        $this->addErrorHelper('error', static function($error) {
            return $error->status === 6;
        }, 'Is your API key correct?');

        $this->addErrorHelper('403', static function() use ($type) {
            return $type === 'lists';
        }, 'Have you reached the maximum number of lists for your MailChimp account?');

        $this->addErrorHelper('404', static function() use ($type) {
            return $type === 'list' || $type === 'listMembers';
        }, 'Is your list_id correct?');

        if (count($this->errors)) {
            $this->response->error = $this->errors;
        }
    }

    /**
     * Add error help text.
     *
     * @param string   $key
     * @param callable $condition
     * @param string   $help
     */
    protected function addErrorHelper(string $key, callable $condition, string $help): void
    {
        if (isset($this->errors[$key])) {
            foreach ($this->errors[$key] as $error) {
                if ($condition) {
                    $error->help[] = $help;
                }
            }
        }
    }

    /**
     * @param string $company
     * @param string $address1
     * @param string $address2
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $country
     * @param string $phone
     *
     * @return MailChimp
     */
    public function setContact(
        $company = '',
        $address1 = '',
        $address2 = '',
        $city = '',
        $state = '',
        $zip = '',
        $country = '',
        $phone = ''
    ): MailChimp {
        $this->contact = [
            'company' => $company,
            'address1' => $address1,
            'address2' => $address2,
            'city'    => $city,
            'state' => $state,
            'zip' => $zip,
            'country' => $country,
            'phone' => $phone,
        ];

        return $this;
    }

    /**
     * @param string $fromName
     * @param string $fromEmail
     * @param string $subject
     * @param string $language
     *
     * @return MailChimp
     */
    public function setCampaignDefault($fromName = '', $fromEmail = '', $subject = '', $language = ''): MailChimp
    {
        $this->campaignDefaults = [
            'from_name' => $fromName,
            'from_email' => $fromEmail,
            'subject' => $subject,
            'language' => $language,
        ];

        return $this;
    }

    /**
     * Get lists.
     *
     * @param array|null $params
     *
     * @return Response
     */
    public function getLists(array $params = []): Response
    {
        $this->currentRequestType = 'lists';

        $this->get('lists', $params);

        return $this->response;
    }

    /**
     * Get list by ID.
     *
     * @param string     $listId
     *
     * @param array|null $params
     *
     * @return Response
     */
    public function getList(string $listId, array $params = []): Response
    {
        $this->currentRequestType = 'list';

        $this->get("lists/{$listId}", $params);

        return $this->response;
    }

    /**
     * @param string $listId
     * @param array  $params
     *
     * @return Response
     */
    public function getListMembers(string $listId, $params = []): Response
    {
        $this->currentRequestType = 'listMembers';

        $this->get("lists/{$listId}/members", $params);

        return $this->response;
    }

    /**
     * @param      $listName
     * @param      $permissionReminder
     * @param bool $emailTypeOption
     *
     * @return Response
     * @throws RequiredPropertyException
     */
    public function createList($listName, $permissionReminder = null, $emailTypeOption = true): Response
    {
        if (!$this->contact) {
            throw new RequiredPropertyException('The contact property has not been set.');
        }
        if (!$this->campaignDefaults) {
            throw new RequiredPropertyException('The campaignDefaults property has not been set.');
        }

        $this->currentRequestType = 'createList';

        if (!$permissionReminder) {
            $permissionReminder = 'You are receiving this email because you opted in via our website.';
        }

        $args = [
            'name' => $listName,
            'contact' => $this->contact,
            'permission_reminder' => $permissionReminder,
            'campaign_defaults' => $this->campaignDefaults,
            'email_type_option' => $emailTypeOption,
        ];

        $this->post('lists', $args);

        return $this->response;
    }

    /**
     * Add an email address to a list.
     *
     * @param string $listId
     * @param string $email
     * @param null   $fname
     * @param null   $phone
     *
     * @return Response
     */
    public function addMember(string $listId, string $email, $fname = null, $phone = null): Response
    {
        $this->currentRequestType = 'addMember';

        $member = [
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => []
        ];

        if ($fname) {
            $member['merge_fields']['FNAME'] = $fname;
        }
        if ($phone) {
            $member['merge_fields']['PHONE'] = $phone;
        }

        $this->post("/lists/{$listId}/members", $member);

        return $this->response;
    }
}
