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
     * @var Response The cURL request response.
     */
    protected $response;

    /**
     * @var array Errors.
     */
    protected $errors = [];

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
     *
     * @return Curl
     */
    public function setOption($option, $value): Curl
    {
        curl_setopt($this->ch, $option, $value);

        return $this;
    }

    /**
     * Send a GET request.
     *
     * @param array $args
     *
     * @return Curl
     */
    public function get($args = []): Curl
    {
        $query = http_build_query($args, '', '&');

        $url = $query ? $this->url . '?' . $query : $this->url;

        curl_setopt($this->ch, CURLOPT_URL, $url);

        $this->request();

        return $this;
    }

    /**
     * Send a POST request.
     *
     * @param array $args
     *
     * @return Curl
     */
    public function post($args = []): Curl
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($args));

        $this->request();

        return $this;
    }

    /**
     * Send a PUT request.
     *
     * @param $args
     *
     * @return Curl
     */
    public function put($args): Curl
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($args));

        $this->request();

        return $this;
    }

    /**
     * Send a PATCH request.
     *
     * @param $args
     *
     * @return Curl
     */
    public function patch($args): Curl
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($args));

        $this->request();

        return $this;
    }

    /**
     * Send a DELETE request.
     *
     * @return Curl
     */
    public function delete(): Curl
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $this->request();

        return $this;
    }

    /**
     * Execute the cURL request.
     *
     * @return void
     */
    protected function request(): void
    {
        $this->response          = new Response();
        $this->response->body    = curl_exec($this->ch);
        $this->response->headers = curl_getinfo($this->ch);
        $this->response->status  = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if (curl_errno($this->ch)) {
            $errorMessage = curl_error($this->ch);
            $errorCode    = curl_errno($this->ch);

            $this->errors[] = ['Error code' => $errorCode, 'Error Message' => $errorMessage];
            $this->response->error = $errorMessage;
            $this->response->status = $errorCode;
        }

        curl_close($this->ch);
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->response->body;
    }

    /**
     * @return mixed
     */
    public function getheaders()
    {
        return $this->response->headers;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->response->status;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->response->error;
    }
}
