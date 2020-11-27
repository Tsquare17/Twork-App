<?php

namespace Twork\Theme\Setup;

use Dotenv\Dotenv;
use Twork\Controller\ControllerDispatcher;
use Twork\Theme\Error\ErrorListener;

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
        if (file_exists(TWORK_PATH . '/.env')) {
            $this->getEnv();
        }

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
        $config = Dotenv::createImmutable(TWORK_PATH);
        $config->load();

        if (isset($_ENV['SMTP']) && $_ENV['SMTP'] === 'true') {
            $config->required('SMTP_HOST');
            $config->required('SMTP_PORT');
            $config->required('SMTP_USER');
            $config->required('SMTP_PASS');

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
        $mailer->SMTPAuth = true;
        $mailer->Host = $_ENV['SMTP_HOST'];
        $mailer->Port = $_ENV['SMTP_PORT'];
        $mailer->Username = $_ENV['SMTP_USER'];
        $mailer->Password = $_ENV['SMTP_PASS'];
        $mailer->SMTPSecure = $_ENV['SMTP_SECURE'] ?? '';
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
