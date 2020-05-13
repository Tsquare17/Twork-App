<?php

namespace Twork\Packages\MailingList;

use Twork\Packages\Http\Curl;

/**
 * Class MailChimp
 * @package Twork\Packages\MailingList
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
     * @var string[] Headers.
     */
    protected $headers;

    /**
     * @var string The URL.
     */
    protected $url;

    /**
     * MailChimp constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        [, $dataCenter] = explode('-', $this->apiKey);

        $this->endpoint = str_replace('<dc>', $dataCenter, $this->endpoint);

        $this->headers = [
            'Authorization: apikey ' . $this->apiKey
        ];
    }

    /**
     * Make a get request to the endpoint.
     *
     * @param $item
     *
     * @return bool|string
     */
    public function get($item)
    {
        $this->url = $this->endpoint . $item;

        $curl = new Curl($this->endpoint . $item, $this->headers);

        return $curl->get();
    }

    /**
     * Get lists.
     *
     * @return array
     */
    public function getLists()
    {
        return json_decode($this->get('lists'));
    }

    /**
     * Get list by ID.
     *
     * @param $listId
     *
     * @return array
     */
    public function getList($listId)
    {
        return json_decode($this->get("/lists/{$listId}"));
    }
}
