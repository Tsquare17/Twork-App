<?php

namespace Twork\App\Controllers;

use Twork\App\Queries\CustomPost;
use Twork\Controller;
use Twork\Packages\Mail\Mail;

/**
 * Class FrontPageController
 * @package Twork\App\Controllers
 */
class FrontPageController extends Controller
{
    /**
     * @var string The name of the blade template.
     */
    public $template = 'homepage';

    /**
     * Return an array of variables to pass to the template.
     *
     * @return array
     */
    public function data()
    {
        $customPost = new CustomPost();

        return [
            'title' => 'Twork',
            'customPosts' => $customPost,
        ];
    }

    /**
     * An example of sending an email.
     */
    public function sendMail()
    {
        $mail = new Mail();
        $mail->to('test@test.com')
            ->name('Joe')
            ->from('sender@test.com')
            ->name('Ted')
            ->subject('Hey There')
            ->template(
                'generic-notification',
                [
                    'notice' => 'Data to pass in as $notice',
                ]
            )
            ->html()
            ->send();
    }

    /**
     * Scripts to be enqueued in the footer.
     *
     * @return array
     */
    public static function footerScripts()
    {
        return [
            'front-page-script-handle' => self::script('/test.min.js', ['jquery']),
        ];
    }

    /**
     * Styles to be enqueued.
     *
     * @return array
     */
    public static function styles()
    {
        return [
            'front-page-style-handle' => self::style('/test.min.css'),
        ];
    }

    /**
     * Scripts to be enqueued with ajax privileges.
     *
     * @return array
     */
    public static function ajaxScripts()
    {
        return [
            'front-page-ajax' => self::script('/ajax.min.js', ['jquery']),
        ];
    }

    /**
     * An array of methods to be allowed access via ajax without being logged in.
     *
     * @return array
     */
    public static function ajaxMethods()
    {
        return [
            'exampleAjaxMethod',
        ];
    }

    /**
     * An example of a method that receives an ajax request.
     */
    public static function exampleAjaxMethod()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'front_page_ajax')) {
            echo 'no';
            wp_die();
        }

        echo 'success';
        wp_die();
    }
}
