<?php

class Saft_Worker_Job
{
    protected $_data = array();

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
