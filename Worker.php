<?php

/**
 * This is an eager worker whome you can give your hard jobs and he will take care of it, when he
 * some spare time.
 */
class Saft_Worker
{
    public static $queueUri = 'http://localhost/jobQueue';
    public static $doneUri = 'http://localhost/doneJobs';
    public static $nsSaft = 'http://saft-framework.org/ns/';

    private $_app;

    public function __construct ($app)
    {
        $this->_app = $app;
    }

    /**
     * Call this method if you have a job and want to hire a worker.
     *
     * @param $job Saft_Worker_Job object with the code to execute
     * @param $data array of data, which is available when the job is executed. This array must be
     *        json serializable.
     */
    public function hire ($job, $data)
    {
        $bootstrap = $this->_app->getBootstrap();
        $model = $bootstrap->getResource('model');

        $jobUri = 'http://localhost/job/' . md5(rand());

        $jobDescription = array(
            $jobUri => array(
                EF_RDF_NS . 'type' => array('value' => self::$nsSaft . 'Job', 'type' => 'uri'),
                self::$nsSaft . 'class' => array('value' => get_class($this), 'type' => 'literal'),
                self::$nsSaft . 'data' => array('value' => json_encode($data), 'type' => 'literal')
            ),
            self::$queueUri => array(
                self::$nsSaft . 'contains' => array('value' => $jobUri, 'type' => 'uri')
            )
        );

        $model->addMultipleStatements($jobDescription);
    }

    public function work ()
    {
        // get queue
        // getjobs from queue
        $bootstrap = $this->_app->getBootstrap();
        $model = $bootstrap->getResource('model');

        $query = 'SELECT ?job ?data ?class' . PHP_EOL;
        $query.= 'WHERE {' . PHP_EOL;
        $query.= '<' . self::$queueUri . '> <' . self::$nsSaft . 'contains> ?job .' . PHP_EOL;
        $query.= '?job <' . self::$nsSaft . 'data> ?data .' . PHP_EOL;
        $query.= '     <' . self::$nsSaft . 'class> ?class .' . PHP_EOL;
        $query.= '}' . PHP_EOL;

        $result = $model->sparqlQuery($query);

        // instantiate job objects and start jobs
        foreach ($result as $jobSet) {
            if (class_exists($jobSet['class'])) {
                $job = new $jobSet['class']();

                // TODO start jobs asynchronously to gain advantage of fast-cgi php
                $job->start($jobSet['data']);

                // add job to the list of done work
                $model->addStatement(
                    self::$doneUri,
                    self::$nsSaft . 'contains',
                    array('value' => $jobSet['job'], 'type' => 'uri')
                );

                // remove job from queue
                $model->deleteMatchingStatements(
                    self::$queueUri,
                    self::$nsSaft . 'contains',
                    array('value' => $jobSet['job'], 'type' => 'uri')
                );
            }
        }

    }
}
