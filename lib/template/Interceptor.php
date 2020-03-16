<?php

namespace Twork\Template;

class Interceptor
{
    protected $templateToOverride;
    protected $overrideTemplate;
    protected $blade;
    protected $data;
    protected $scripts;
    protected $styles;

    public function __construct($templateToOverride, $overrideTemplate, $blade, $data)
    {
        $this->templateToOverride = $templateToOverride;
        $this->overrideTemplate = $overrideTemplate;
        $this->blade = $blade;
        $this->data = $data;
    }

    public function templateInterceptor($template)
    {
        if ($template === TWORK_PATH . '/' . $this->templateToOverride) {
            add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);

            echo $this->blade->render($this->overrideTemplate, $this->data);
            return;
        }

        return $template;
    }

    public function registerScripts(array $scripts)
    {
        if (empty($scripts)) {
            return;
        }

        $registeredScripts = [];
        foreach ($scripts as $handle => $script) {
            $script['handle'] = $handle;
            $registeredScripts[] = $script;
        }

        if (empty($this->scripts)) {
            $this->scripts = $registeredScripts;
        } else {
            $this->scripts = array_merge($this->scripts, $registeredScripts);
        }
    }

    public function registerStyles(array $styles)
    {
        $registeredStyles = [];
        foreach ($styles as $handle => $style) {
            $style['handle'] = $handle;
            $registeredStyles[] = $style;
        }

        if (empty($this->styles)) {
            $this->styles = $registeredStyles;
        } else {
            $this->styles = array_merge($this->styles, $registeredStyles);
        }
    }

    public function enqueueAssets()
    {
        $this->enqueueScripts();
        $this->enqueueStyles();
    }

    protected function enqueueScripts()
    {
        if (empty($this->scripts)) {
            return;
        }

        foreach ($this->scripts as $script) {
            wp_enqueue_script(
                $script['handle'],
                $script['path'],
                $script['dependencies'],
                $script['version'],
                $script['in_footer']
            );
        }
    }

    protected function enqueueStyles()
    {
        foreach ($this->styles as $style) {
            wp_enqueue_style(
                $style['handle'],
                $style['path'],
                $style['dependencies'],
                $style['version']
            );
        }
    }
}
