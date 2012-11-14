<?php

class Saft_Worker
{
    public static $queueUri = 'http://localhost/jobQueue';
    public static $nsSaft = 'http://saft-framework.org/ns/';

    public function work ($app)
    {
        // get queue
        // getjobs from queue
        $bootstrap = $app->getBootstrap();
        $model = $bootstrap->getResource('model');

        $query = 'SELECT ?job ?data ?class' . PHP_EOL;
        $query.= 'WHERE {' . PHP_EOL;
        $query.= '<' . self::$queueUri . '> <' . self::$nsSaft . 'contains> ?job .' . PHP_EOL;
        $query.= '?job <' . self::$nsSaft . 'data> ?data .' . PHP_EOL;
        $query.= '     <' . self::$nsSaft . 'class> ?class .' . PHP_EOL;
        $query.= '}' . PHP_EOL;

        $result = $model->sparqlQuery($query);

        // instantiate job objects and start jobs
        $jobList = array();

        foreach ($result as $jobSet) {
            if (class_exists($jobSet['class'])) {
                $job = new $jobSet['class']();
                $job->start($jobSet['data']);

                // remove job from queue
            }
        }

    }
}
