<?php

class Saft_Worker_Job
{
    public static $nsSaft = 'http://saft-framework.org/ns/';
    private $_runCount;
    protected $_data = array();

    public function __construct ()
    {
    }

    public function prepare ($app, $data = array())
    {
        $bootstrap = $app->getBootstrap();
        $model = $bootstrap->getResource('model');

        $jobUri = 'http://localhost/job/' . md5(rand());

        $jobDescription = array(
            $jobUri => array(
                EF_RDF_NS . 'type' => array('value' => self::$nsSaft . 'Job', 'type' => 'uri'),
                self::$nsSaft . 'class' => array('value' => get_class($this), 'type' => 'literal'),
                self::$nsSaft . 'data' => array('value' => json_encode($data), 'type' => 'literal')
            ),
            Saft_Worker::$queueUri => array(
                self::$nsSaft . 'contains' => array('value' => $jobUri, 'type' => 'uri')
            )
        );

        $model->addMultipleStatements();
    }

    public function start ($dataString)
    {
        $this->_data = json_decode($dataString);
        $this->run();
    }

    /**
     * This method has to be implemented by every Job. It will be executed as the job.
     */
    public abstract function run ();
}
