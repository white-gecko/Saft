<?php
class Saft_Layout {
    private $_app;
    private $_layoutEnabled = true;

    private $_responseCode = null;
    private $_header = array();
    private $_contentFiles = array();
    private $_layout = null;
    private $_rawContent = null;
    private $_rawFile = null;
    private $_debugLog = '';
    private $_debug = true;
    private $_placeholders = array();

    private $_options = array();

    public function __construct ($app)
    {
        $this->_app = $app;
    }

    public function __set ($name, $value)
    {
        $this->_options[$name] = $value;
    }

    public function addContent ($contentFile) {
        $this->_contentFiles[] = $contentFile;
    }

    public function setLayout ($layout) {
        $this->_layout = $layout;
    }

    public function setPlaceholder ($name, Saft_Template $placeholder)
    {
        $this->_placeholders[$name] = $placeholder;
    }

    public function disableLayout () {
        $this->_layoutEnabled = false;
    }

    public function setRawContent ($rawContent) {
        $this->disableLayout();
        $this->_rawContent = $rawContent;
    }

    public function setRawFile ($rawFile) {
        $this->disableLayout();
        $this->_rawFile = $rawFile;
    }

    /**
     * Set a HTTP header field for the response
     * @todo I don't know if the header fields are case sensitive
     */
    public function setHeader ($field, $value)
    {
        $this->_header[$field] = $value;
    }

    /**
     * Set a HTTP response code
     */
    public function setResponseCode ($responseCode)
    {
        $this->_responseCode = $responseCode;
    }

    /**
     * With the method the browser can be redirected to a new location
     */
    public function redirect ($location, $responseCode = 303)
    {
        $this->_responseCode = $responseCode;
        $this->setHeader('Location', $location);
    }

    /**
     * This method sends the template and header to the browser
     */
    public function render ()
    {
        if ($this->_responseCode !== null) {
            http_response_code($this->_responseCode);
        }

        foreach ($this->_header as $field => $value) {
            header($field . ': ' . $value);
        }

        if ($this->_layoutEnabled) {
            $options = $this->_options;
            $this->setPlaceholder('main.content', $this->_getMainContent());
            $template = new Saft_Template_Layout($this->_layout, $options, $this->_placeholders);
            $template->render();
        } else {
            if ($this->_rawContent !== null) {
                echo $this->_rawContent;
            } else if ($this->_rawFile !== null) {
                readfile($this->_rawFile);
            }
        }
    }

    private function _getMainContent ()
    {
        $bootstrap = $this->_app->getBootstrap();
        $request = $bootstrap->getResource('request');
        $controller = $request->getValue('c', 'get');
        $action = $request->getValue('a', 'get');
        return new Saft_Template($controller . '/' . $action . '.phtml', $this->_options);
    }

}
