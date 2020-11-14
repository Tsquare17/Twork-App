<?php

namespace Twork\App\Controllers;

use Twork\App\Queries\CustomPostQuery;
use Twork\Controller\Controller;

/**
 * Class FrontPageController
 * @package Twork\App\Controllers
 */
class FrontPageController extends Controller
{
    /**
     * Return an array of variables to pass to the template.
     *
     * @return array
     */
    public function data()
    {
        $customPost = new CustomPostQuery();

        return [
            'title' => 'Twork',
            'customPosts' => $customPost,
        ];
    }

    /**
     * Scripts to be enqueued in the footer.
     *
     * @return array
     */
    public static function footerScripts()
    {
        return [
            'front-page-script-handle' => self::script(TWORK_JS_URL . '/test.min.js', ['jquery'], TWORK_VERSION),
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
            'front-page-style-handle' => self::style(TWORK_CSS_URL . '/test.min.css', TWORK_VERSION),
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
            'front-page-ajax' => self::script(TWORK_JS_URL . '/ajax.min.js', ['jquery']),
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
