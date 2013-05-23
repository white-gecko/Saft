<?php
class Saft_Template {
    private static $_instance = null;

    private $_layoutEnabled = true;

    private $_responseCode = null;
    private $_header = array();
    private $_contentFiles = null;
    private $_menuFiles = null;
    private $_layout = null;
    private $_rawContent = null;
    private $_debugLog = '';

    public static function getInstance ()
    {
        if (self::$_instance == null) {
            self::$_instance = new Saft_Template();
        }
        return self::$_instance;
    }

    public function __construct () {
        if ($this->_menuFiles === null) {
            $this->_menuFiles = array();
        }
        if ($this->_contentFiles === null) {
            $this->_contentFiles = array();
        }
    }

    public function addMenu ($menuFile) {
        $this->_menuFiles[] = $menuFile;
    }

    public function addContent ($contentFile) {
        $this->_contentFiles[] = $contentFile;
    }

    public function setLayout ($layout) {
        $this->_layout = $layout;
    }

    public function addDebug ($debugString) {
        $this->_debugLog .= $debugString . "\n";
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
            include $this->_layout;
        } else {
            echo $this->_rawContent;
        }
    }

}
