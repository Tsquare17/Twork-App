<?php

namespace Twork\Packages\MailingList\MailChimp;

/**
 * Class MailChimpError
 * @package Twork\Packages\MailingList\MailChimp
 */
class MailChimpError
{
    /**
     * @var string The url of the endpoint.
     */
    public $route;

    /**
     * @var int The status code.
     */
    public $status;

    /**
     * @var string Details about the error.
     */
    public $details;

    /**
     * @var array An array of questions to assist with vague MailChimp responses.
     */
    public $help = [];
}
