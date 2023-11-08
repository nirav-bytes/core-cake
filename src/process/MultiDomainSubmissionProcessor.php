<?php

namespace BytesNirav\CakeCorePhp\process;

use BytesNirav\CakeCorePhp\process\routes\Store;

class MultiDomainSubmissionProcessor
{
    private $init;

    public function __construct()
    {
        date_default_timezone_set('America/Los_Angeles');
        $this->init = new Init();
    }

    public function processRequest()
    {
        try {
            $fields = $_POST;
            $fileName = $this->getFileName();
            $this->includeFile($fileName, $fields);
        } catch (\Throwable $th) {
            // Log or handle the error appropriately
            error_log("Error while processing request: " . $th->getMessage());
        }
    }

    private function getFileName()
    {
        $requestUri = strtok($_SERVER['REQUEST_URI'], '?');
        $fileName = './routes/' . trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $requestUri), '/') . '.php';
        return $fileName;
    }

    private function includeFile($fileName, $fields)
    {
        if (file_exists($fileName)) {
            require_once $fileName;
        } else {
            $this->handleNonExistentFile($fields);
        }
    }

    private function handleNonExistentFile($fields)
    {
        $commonFields = $this->init->getCommonFields();
        $db = $this->init->getDBConnection();
        $formProcessor = new Store($commonFields, $fields, $db);
        // Log or handle the formProcessor result appropriately
        error_log("Form processor result: " . print_r($formProcessor, true));
        exit;
    }
}

// Example usage:
$processor = new MultiDomainSubmissionProcessor();
$processor->processRequest();
