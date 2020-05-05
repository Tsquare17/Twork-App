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

        $this->ch = curl_init();
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
     * Get the requested resource.
     *
     * @param array $args
     *
     * @return bool|string
     */
    public function get($args = [])
    {
        $query = http_build_query($args, '', '&');
        curl_setopt($this->ch, CURLOPT_URL, $this->url . '?' . $query);

        $response = curl_exec($this->ch);

        curl_close($this->ch);

        return $response;
    }
}
