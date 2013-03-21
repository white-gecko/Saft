<?php

/**
 * This Class is responsible to initialize resources and returning them on request.
 * It keeps a registry of the already initialized resources to not have to the work twice.
 */
class Saft_Bootstrap
{
    /**
     * A reference to the Application instance
     */
    protected $_app;

    /**
     * The registry of already initialized resources
     */
    private $_resources;

    /**
     * The constructor of the Bootstrap class, should be called only once
     *
     * @param $app the instance of the Application
     */
    public function __construct ($app)
    {
        $this->_app = $app;
        $this->_resources = array();
    }

    /**
     * The descructor can be used to cleanup resources e.g. close file handlers
     */
    public function __destruct ()
    {
    }

    /**
     * Gives the requested resource from the registry, or initializes it if this wasn't done before
     *
     * @param $resourceName the name of the resource, which is needed
     * @return the resources object
     */
    public function getResource ($resourceName)
    {
        $resourceName = ucfirst(strtolower($resourceName));

        if (!isset($this->_resources[$resourceName])) {
            $initMethod = 'init' . $resourceName;

            if (method_exists($this, $initMethod)) {
                $this->_resources[$resourceName] = $this->$initMethod();
            } else {
                throw new Exception('The resource "' . $resourceName . '" is not defined in Bootstrap');
            }
        }

        return $this->_resources[$resourceName];
    }

    /**
     * Invalidate a resource if it has to be reinitialized
     *
     * @param $resourceName the name of the resource to invalidate
     */
    public function invalidateResource ($resourceName)
    {
        $resourceName = ucfirst(strtolower($resourceName));
        unset($this->_resources[$resourceName]);
    }

    /**
     * Initializes the Request object
     */
    protected function initRequest ()
    {
        $store = $this->getResource('session');

        $values = array();
        $values['session'] = $_SESSION;
        $values['get'] = $_GET;
        $values['post'] = $_POST;

        $values['all'] = array();

        foreach (array_keys($_GET) as $key) {
            $values['all'][$key] = 'get';
        }

        foreach (array_keys($_POST) as $key) {
            $values['all'][$key] = 'post';
        }

        foreach (array_keys($_SESSION) as $key) {
            $values['all'][$key] = 'session';
        }

        $request = new Saft_Request($_SERVER['REQUEST_METHOD'], $values);

        $body = file_get_contents('php://input');

        if (!empty($body)) {
            $request->setBody($body);
        }

        return $request;
    }

    protected function initWorker ()
    {
        return new Saft_Worker($this->_app);
    }

    /**
     * Initializes the Logger Object
     */
    protected function initLogger ()
    {
        return new Saft_Logger($this->_app);
    }
}
