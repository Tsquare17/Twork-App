<?php

namespace Twork\Theme\Setup;

use Dotenv\Dotenv;
use Twork\Controller\ControllerDispatcher;

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
        new GlobalScripts();
    }

    /**
     * Enqueue global styles.
     */
    public function enqueueGlobalStyles(): void
    {
        new GlobalStyles();
    }

    /**
     * Load env and setup environment.
     */
    public function getEnv(): void
    {
        if (file_exists(TWORK_PATH . '/.env')) {
            $config = Dotenv::createImmutable(TWORK_PATH);
            $config->load();
        }

        if (getenv('SMTP') === 'true') {
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
        $config = require TWORK_PATH . '/config/config.php';

        new ControllerDispatcher($config);
    }
}
