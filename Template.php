<?php
class Saft_Template
{
    private $_file;
    private $_options;

    public function __construct ($file, $options = array())
    {
        $this->_file = $file;
        $this->_options = $options;
    }

    public function __get ($name)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        } else {
            return null;
        }
    }

    public function __isset ($name)
    {
        return isset($this->_options[$name]);
    }

    public function partial ($file, $options = array())
    {
        $partial = new Saft_Template('partials/' . $file, $options);
        $partial->render();
    }

    /**
     * This method renders the output HTML
     */
    public function render ()
    {
        include 'templates/' . $this->_file;
    }

}
