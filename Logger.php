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

        // Open the log file. Warnings are suppressed, to avoid notification if file does not exist.
        $this->_file = @fopen($filePath, 'a');

        // Write information to log, if log file couldn't be opened
        if ($this->_file == false) {
            $this->_log .= 'Log can\'t be written to file because file couln\'t be opened';
        }
    }

    public function __destruct ()
    {
        if ($this->_file !== false) {
            fclose($this->_file);
        }
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
        if ($this->_file !== false) {
            fwrite($this->_file, $line);
        }
    }
}
