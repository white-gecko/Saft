<?php

class Saft_Logger
{
    private $_log = '';
    private $_file;

    public function __construct ($app, $filePath = null)
    {
        if ($filePath === null) {
            $filePath = $app->getBaseDir() . '/xodx.log';
        }

        $this->_file = fopen($filePath, 'a');
    }

    public function __destruct ()
    {
        fclose($this->_file);
    }

    public function getLastLog ()
    {
        return $this->_log;
    }

    public function debug ($message) {
        $this->write(time() . ' - ' . ' DEBUG: ' . $message . PHP_EOL);
    }

    public function info ($message) {
        $this->write(time() . ' - ' . ' INFO: ' . $message . PHP_EOL);
    }

    public function error ($message) {
        $this->write(time() . ' - ' . ' ERROR: ' . $message . PHP_EOL);
    }

    private function write ($line) {
        $this->_log .= $line;
        fwrite($this->_file, $line);
    }
}
