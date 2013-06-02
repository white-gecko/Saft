<?php
class Saft_Layout {
    private static $_instance = null;

    private $_layoutEnabled = true;

    private $_responseCode = null;
    private $_header = array();
    private $_contentFiles = array();
    private $_layout = null;
    private $_rawContent = null;
    private $_debugLog = '';
    private $_debug = true;

    private $_options = array();

    public static function getInstance ()
    {
        if (self::$_instance == null) {
            self::$_instance = new Saft_Layout();
        }
        return self::$_instance;
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

    public function disableDebug () {
        $this->_debug = false;
    }

    public function addDebug ($debugString) {
        $this->_debugLog .= $debugString . PHP_EOL;
    }

    public function disableLayout () {
        $this->_layoutEnabled = false;
    }

    public function setRawContent ($rawContent) {
        $this->_rawContent = $rawContent;
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
            $options['_contentFiles'] = $this->_contentFiles;
            $options['_debug'] = $this->_debug;
            $options['_debugLog'] = trim($this->_debugLog);
            $template = new Saft_Template($this->_layout, $options);
            $template->render();
        } else {
            echo $this->_rawContent;
        }
    }

}
