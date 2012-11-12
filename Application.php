<?php

require_once 'Bootstrap.php';

class Saft_Application
{
    protected $_appNamespace = null;

    private $_bootstrap = null;
    private $_baseUri = null;
    private $_baseDir = null;

    private $_controllers = array();

    public function getBootstrap()
    {
        if (!isset($this->_bootstrap)) {
            if (class_exists('Bootstrap')) {
                $this->_bootstrap = new Bootstrap($this);
            } else {
                $this->_bootstrap = new Saft_Bootstrap($this);
            }
        }

        return $this->_bootstrap;
    }

    public function getController ($controllerName)
    {
        if (!isset($this->_controllers[$controllerName])) {
            if (class_exists($controllerName)) {
                $this->_controllers[$controllerName] = new $controllerName($this);
            } else {
                throw new Exception('The specified controller "' . $controllerName . '" does not exist');
            }
        }

        return $this->_controllers[$controllerName];
    }

    public function run() {
        // TODO move some code here
    }

    public function setBaseUri ($baseUri)
    {
        $this->_baseUri = $baseUri;
    }

    public function getBaseUri ()
    {
        return $this->_baseUri;
    }

    public function setBaseDir ($baseDir)
    {
        $this->_baseDir = $baseDir;
    }

    public function getBaseDir ()
    {
        return $this->_baseDir;
    }

    public function setAppNamespace ($namespace)
    {
        $this->_appNamespace = $namespace;
    }
}
