<?php

namespace Twork;

use Dotenv\Dotenv;

/**
 * Class Setup
 * @package Twork
 */
class Setup
{
    /**
     * Setup constructor.
     */
    public function __construct()
    {
        $this->getEnv();

        add_action('wp_enqueue_scripts', [$this, 'enqueueGlobalScripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueGlobalStyles']);

        $this->run();
    }

    /**
     * Enqueue global scripts.
     */
    public function enqueueGlobalScripts(): void
    {
        wp_enqueue_script('twork-js', TWORK_JS_URL . '/twork.min.js', ['jquery'], TWORK_VERSION, true);
    }

    /**
     * Enqueue global styles.
     */
    public function enqueueGlobalStyles(): void
    {
        wp_enqueue_style('twork-stylesheet', get_stylesheet_uri(), null, TWORK_VERSION);
        wp_enqueue_style('twork-css', TWORK_CSS_URL . '/twork.min.css', null, TWORK_VERSION);
    }

    /**
     * Load env and setup environment.
     */
    public function getEnv(): void
    {
        $config = Dotenv::createImmutable(TWORK_PATH);
        $config->load();

        if (getenv('SMTP')) {
            add_filter('phpmailer_init', [$this, 'useSmtp']);
        }
    }

    /**
     * Set SMTP configuration.
     *
     * @param $mailer
     */
    public function useSmtp($mailer): void
    {
        $mailer->isSMTP();
        $mailer->Host = getenv('SMTP_HOST');
        $mailer->SMTPAuth = true;
        $mailer->Port = getenv('SMTP_PORT');
        $mailer->Username = getenv('SMTP_USER');
        $mailer->Password = getenv('SMTP_PASS');
    }

    /**
     * Run Theme, Run!
     */
    protected function run(): void
    {
        new Theme();
    }
}
