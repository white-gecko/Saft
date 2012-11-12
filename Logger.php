<?php

class Saft_Logger
{

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

    public function info ($message) {
        fwrite($this->_file, time() . ' - ' . $message . "\n");
    }
}
