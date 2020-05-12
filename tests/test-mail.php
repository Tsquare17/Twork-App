<?php

namespace Twork\Tests;

use Twork\Packages\Mail\Mail;
use WP_UnitTestCase;

/**
 * Class QueryTest
 *
 * Query test case.
 *
 * @package Twork
 */
class MailTest extends WP_UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        reset_phpmailer_instance();

        switch_theme('twork');
    }

    /** @test */
    public function can_send_mail(): void
    {
        $mockMailer = tests_retrieve_phpmailer_instance();

        $to      = 'test2@test.com';
        $subject = 'testsubject';
        $body    = 'testmessage';

        $mail = new Mail();
        $mail->to($to)
            ->subject($subject)
            ->body($body)
            ->send();

        $this->assertContains($to, ($mockMailer->get_recipient('to')->address));
        $this->assertSame($subject, $mockMailer->get_sent()->subject);
        $this->assertSame($body . "\n", $mockMailer->get_sent()->body);
    }

    /** @test */
    public function can_send_template(): void
    {
        $mockMailer = tests_retrieve_phpmailer_instance();

        $to      = 'test2@test.com';
        $subject = 'testsubject';
        $notice  = 'test1234';

        $mail = new Mail();
        $mail->to($to)
             ->subject($subject)
             ->template('generic-notification', [
                 'notice' => $notice,
             ])
             ->send();

        $this->assertSame($notice . "\n", $mockMailer->get_sent()->body);
    }

    /** @test */
    public function can_send_to_multiple_recipients(): void
    {
        $mockMailer = tests_retrieve_phpmailer_instance();

        $to      = 'test1@test.com';
        $to2     = 'test2@test.com';

        $mail = new Mail();
        $mail->to($to)
             ->name('test')
             ->to($to2)
             ->name('test2')
             ->subject('subject')
             ->body('test')
             ->groupMessage()
             ->send();

        $this->assertContains($to, $mockMailer->get_recipient('to', 0, 0)->address);
        $this->assertContains($to2, $mockMailer->get_recipient('to', 0, 1)->address);
    }
}
