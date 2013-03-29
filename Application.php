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

    public function getHelper ($helperName)
    {
        if (!isset($this->_helpers[$helperName])) {
            if (class_exists($helperName)) {
                $this->_helpers[$helperName] = new $helperName($this);
            } else {
                throw new Exception('The specified helper "' . $helperName . '" does not exist');
            }
        }

        return $this->_helpers[$helperName];
    }

    public function run() {
        // TODO move some code here
    }

    /**
     * This method starts the Application in the Job processing mode insted of normal server mode
     * it creates a Worker and processes all outstanding jobs in the queue
     */
    public function runJobs() {
        $worker = new Saft_Worker($this);
        $worker->work();
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
