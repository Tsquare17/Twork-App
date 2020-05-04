<?php

namespace Twork\Debug;

class DebugMail
{
    public function __construct()
    {
        add_action('phpmailer_init', [$this, 'helo']);
    }

    public function helo($phpmailer): void
    {
        $phpmailer->isSMTP();
        $phpmailer->Host = '127.0.0.1';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'Mailbox-Name';
        $phpmailer->Password = '';
    }
}
