<?php

namespace Twork\Packages\Http;

/**
 * Class Curl
 * @package Twork\Packages\Http
 */
class Curl
{
    /**
     * @var false|resource The cURL handle.
     */
    protected $ch;

    /**
     * @var string The URL.
     */
    protected $url;

    /**
     * Curl constructor.
     *
     * @param $url
     * @param array $headers
     * @param bool $verifySsl
     */
    public function __construct($url, $headers = [], $verifySsl = true)
    {
        $this->url = $url;
        $this->ch  = curl_init();

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
    }

    /**
     * Set a cURL option.
     *
     * @param $option
     * @param $value
     */
    public function setOption($option, $value): void
    {
        curl_setopt($this->ch, $option, $value);
    }

    /**
     * Send a GET request.
     *
     * @param array $args
     *
     * @return bool|string
     */
    public function get($args = [])
    {
        $query = http_build_query($args, '', '&');

        curl_setopt($this->ch, CURLOPT_URL, $this->url . '?' . $query);

        return $this->request();
    }

    /**
     * Send a POST request.
     *
     * @param array $args
     *
     * @return bool|string
     */
    public function post($args = [])
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($args));

        return $this->request();
    }

    /**
     * Send a PUT request.
     *
     * @param $args
     *
     * @return bool|string
     */
    public function put($args)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($args));

        return $this->request();
    }

    /**
     * Send a PATCH request.
     *
     * @param $args
     *
     * @return bool|string
     */
    public function patch($args)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($args));

        return $this->request();
    }

    /**
     * Send a DELETE request.
     *
     * @return bool|string
     */
    public function delete()
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->request();
    }

    /**
     * Execute the cURL request.
     *
     * @return bool|string
     */
    protected function request()
    {
        $response = curl_exec($this->ch);

        curl_close($this->ch);

        return $response;
    }
}
