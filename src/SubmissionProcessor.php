<?php

namespace BytesNirav\CakeCorePhp;

// use Cake\Something; // Make sure to replace with the actual namespace

class SubmissionProcessor
{
    public function __construct()
    {
        session_start();
        date_default_timezone_set('America/Los_Angeles');
        include_once('./vendor/autoload.php');
        include_once('init.php');
    }

    public function processRequest()
    {
        $fileName = './routes/' . trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', strtok($_SERVER['REQUEST_URI'], '?')), '/') . '.php';

        if (file_exists($fileName)) {
            require_once($fileName);
        } else {
            require_once('./routes/store.php');
        }
    }
}

// // Usage
// $processor = new BytesNirav\CakeCorePhp\SubmissionProcessor();
// $processor->processRequest();
