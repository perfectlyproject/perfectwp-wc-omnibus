<?php

namespace PerfectWPWCO\Utils;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;

class Template
{
    /**
     * Path to file template
     *
     * @var string
     */
    private $template;

    /**
     * List of params for template, those params will be available as variables in template
     *
     * @var string
     */
    private $params = [];

    /**
     * Js languages will be injected to global pp object
     *
     * @var string
     */
    public static $jsLanguages = [];

    /**
     * Js data will be injected to global pp object
     *
     * @var array
     */
    public static $jsVariableData = [];

    /**
     * Set the core plugin path
     *
     * @param string $template
     * @return \PerfectWPWCO\Utils\Template
     */
    public function setPath($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setParam($key, $value)
    {

        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Setter params
     *
     * @param array $params
     *
     * @return $this
     */
    public function setParams($params)
    {

        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Adding javascript params
     *
     * @param array $params
     */
    public function setJsLanguages($params)
    {

        Plugin::getInstance()->get('assets')->addJsTranslation($params);

        return $this;
    }

    /**
     * Adding javascript params
     *
     * @param array $params
     */
    public function setJsVariables($params)
    {

        Plugin::getInstance()->get('assets')->addJsData($params);

        return $this;
    }

    /**
     * Render template
     *
     * @return string
     */
    public function render()
    {

        if (!empty($this->params) && is_array($this->params)) {
            extract($this->params);
        }

        ob_start();
        require $this->template;
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Print template
     */
    public function display()
    {

        echo $this->render();
    }
}