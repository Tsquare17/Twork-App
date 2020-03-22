<?php

namespace Twork;

abstract class Controller
{
    /**
     * @var string Name of the blade template.
     */
    public $template;

    /**
     * Return an array of variables to pass to the template.
     *
     * @return array
     */
    public function data()
    {
        return [];
    }

    /**
     * Scripts to be enqueued in the footer.
     *
     * @return array
     */
    public function footerScripts()
    {
        return [];
    }

    /**
     * Scripts to be enqueued in the header.
     *
     * @return array
     */
    public function headerScripts()
    {
        return [];
    }

    /**
     * Styles to be enqueued.
     *
     * @return array
     */
    public function styles()
    {
        return [];
    }

    /**
     * Shorthand script specification.
     *
     * @param       $path
     * @param array $dependencies
     *
     * @return array
     */
    protected function script($path, array $dependencies = null): array
    {
        return ['path' => TWORK_JS_URL . $path, 'dependencies' => $dependencies, 'version' => TWORK_VERSION];
    }

    /**
     * Shorthand style specification.
     *
     * @param            $path
     * @param array|null $dependencies
     *
     * @return array
     */
    protected function style($path, array $dependencies = null): array
    {
        return ['path' => TWORK_CSS_URL . $path, 'dependencies' => $dependencies, 'version' => TWORK_VERSION];
    }

    /**
     * Render Blade template.
     */
    public function render(): void
    {
        echo Theme::getBlade()->render($this->template, $this->data());
    }
}
