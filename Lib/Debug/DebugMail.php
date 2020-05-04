<?php

namespace Twork\Debug;

/**
 * Class DebugMail
 * @package Twork\Debug
 */
class DebugMail
{
    /**
     * DebugMail constructor.
     */
    public function __construct()
    {
        add_action('phpmailer_init', [$this, 'helo']);
    }

    /**
     * Override the mail settings to be intercepted by HELO.
     * https://usehelo.com/
     *
     * @param $phpmailer
     */
    public function helo($phpmailer): void
    {
        $phpmailer->isSMTP();
        $phpmailer->Host = '127.0.0.1';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'Twork';
        $phpmailer->Password = '';
    }
}
