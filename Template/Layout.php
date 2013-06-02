<?php
class Saft_Template_Layout extends Saft_Template
{
    /**
     * An array of Saft_Templates
     */
    protected $_placeholders;

    public function __construct ($file, $options = array(), $placeholders = array())
    {
        // Call the parent constructor
        parent::__construct($file, $options);
        $this->_placeholders = $placeholders;
    }

    public function has ($placeholder)
    {
        return isset($this->_placeholders[$placeholder]);
    }

    public function placeholder ($placeholder)
    {
        $this->_placeholders[$placeholder]->render();
    }
}
