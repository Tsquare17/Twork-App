<?php

namespace Twork\Packages\Mail;

use Twork\Theme;
use WP_Error;

/**
 * Class Mail
 * @package Twork\Packages\Mail
 */
class Mail
{
    /**
     * @var string The mail sender.
     */
    private $from;

    /**
     * @var string|array The mail recipient(s).
     */
    private $to;

    /**
     * @var string The mail subject.
     */
    protected $subject;

    /**
     * @var array The mail headers.
     */
    protected $headers = [];

    /**
     * @var string The mail message body.
     */
    protected $body;

    /**
     * @var array The mail attachments.
     */
    protected $attachments = [];

    /**
     * @var string The mail content type.
     */
    protected $contentType = 'Content-Type: text/plain; charset=UTF-8';

    /**
     * @var bool Send the mail together or separately.
     */
    protected $groupMessage = false;

    /**
     * @var WP_Error The last wp_mail error.
     */
    protected $error;

    /**
     * @var int The next index of $this->to that would be set.
     */
    private $nextToIndex = 0;

    /**
     * @var bool True if the last address add method was "to".
     */
    private $lastCallTypeIsTo;

    /**
     * Mail constructor.
     */
    public function __construct()
    {
        add_action('wp_mail_failed', [$this, 'setError']);
    }

    /**
     * Set the from argument.
     *
     * @param string $from
     *
     * @return $this
     */
    public function from(string $from): Mail
    {
        $this->from = $from;

        $this->lastCallTypeIsTo = false;

        return $this;
    }

    /**
     * Set the to argument.
     *
     * @param $to
     *
     * @return $this
     */
    public function to($to): Mail
    {
        $this->to[$this->nextToIndex] = $to;

        $this->lastCallTypeIsTo = true;
        $this->nextToIndex++;

        return $this;
    }

    /**
     * Set a name for the most recently added address.
     *
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): Mail
    {
        if (!$this->lastCallTypeIsTo) {
            $this->from = "{$name} <{$this->from}>";

            return $this;
        }

        $this->to[$this->nextToIndex - 1] = "$name <{$this->to[$this->nextToIndex - 1]}>";

        return $this;
    }

    /**
     * Set the subject.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function subject(string $subject): Mail
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set Cc headers.
     *
     * @param string $cc
     *
     * @return $this
     */
    public function cc(string $cc): Mail
    {
        $this->headers[] = "Cc: {$cc}";

        return $this;
    }

    /**
     * Set Bcc headers.
     *
     * @param string $bcc
     *
     * @return $this
     */
    public function bcc(string $bcc): Mail
    {
        $this->headers[] = "Bcc: {$bcc}";

        return $this;
    }

    /**
     * Set the content type.
     *
     * @param bool $isHtml
     *
     * @return $this
     */
    public function html(bool $isHtml = true): Mail
    {

        $this->contentType = $isHtml
            ? 'Content-Type: text/html; charset=UTF-8'
            : 'Content-Type: text/plain; charset=UTF-8';

        return $this;
    }

    /**
     * Send as a group message, or separately.
     *
     * @param bool $groupMessage
     *
     * @return $this
     */
    public function groupMessage(bool $groupMessage = true): Mail
    {
        $this->groupMessage = $groupMessage;

        return $this;
    }

    /**
     * Set the mail body.
     *
     * @param string $body
     *
     * @return $this
     */
    public function body(string $body): Mail
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Send mail body using a template.
     *
     * @param string $path
     * @param array  $vars
     *
     * @return $this
     */
    public function template(string $path, array $vars = []): Mail
    {
        $this->body = Theme::getBlade()->render('emails.' . $path, $vars);

        return $this;
    }

    /**
     * Add an attachment.
     *
     * @param $attachment
     *
     * @return $this
     */
    public function attachment($attachment): Mail
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Send mail.
     *
     * @return bool
     */
    public function send(): bool
    {
        $this->headers[] = "From: {$this->from}";
        $this->headers[] = $this->contentType;

        if ($this->groupMessage) {
            return wp_mail($this->to, $this->subject, $this->body, $this->headers, $this->attachments);
        }

        $successArray = [];
        foreach ($this->to as $to) {
            $successArray[] = wp_mail($to, $this->subject, $this->body, $this->headers, $this->attachments);
        }

        return in_array(false, $successArray, true);
    }

    /**
     * Set if there is an error sending mail.
     *
     * @param WP_Error $error
     */
    public function setError($error): void
    {
        $this->error = $error;
    }

    /**
     * Get the last error or null.
     *
     * @return WP_Error|null
     */
    public function getError(): ?WP_Error
    {
        return $this->error;
    }

    /**
     * Reset all properties.
     *
     * @return Mail
     */
    public function reset(): Mail
    {
        return new self();
    }
}
