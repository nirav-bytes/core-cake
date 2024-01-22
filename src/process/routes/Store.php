<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class Store
{
    private $commonFields;
    private $fields;
    private $DB;

    public function __construct($fields, $commonFields, $DB)
    {
        $this->fields = $fields;
        $this->commonFields = $commonFields;
        $this->DB = $DB;
        $this->checkEmptyField();
        $this->formDataProcessor();
    }

    public function checkEmptyField()
    {
        $checkFieldData = new CheckFieldData($this->fields, $this->commonFields, $this->DB);
    }

    public function formDataProcessor()
    {
        $formDataProcessor = new FormDataProcessor($this->fields, $this->commonFields);
        $processedData = $formDataProcessor->getFieldData();

        // Create an instance of Validationphone
        $validate = new Validationphone();
        $ValidationPhoneNumber = $validate->validatePhoneNumber($processedData);
        $processedData = $ValidationPhoneNumber['processedData'];
        $nd_odlv = $ValidationPhoneNumber['nd_odlv'];

        // Create an instance of InfutorProcessor
        $infutorData = new InfutorProcessor();
        $neausterFire = $infutorData->processInfutorResponse($processedData);

        $coookie = new CookieManager;
        $coookie->setCookie('tax_debt', $processedData['tax_debt']);
        $coookie->setCookie('first_name', $processedData['first_name']);
        $coookie->setCookie('last_name', $processedData['last_name']);
        $coookie->setCookie('primary_phone', $processedData['primary_phone']);
        $coookie->setCookie('email_address', $processedData['email_address']);
        $coookie->setCookie('state', $processedData['state']);


        $formHandler = new FormDataHandler($neausterFire, $nd_odlv);
        $formHandlerdata = $formHandler->processFormData();

        // $checkUnivarsalLeadId = UniversalLeadid::authuniversalLeadid($formHandlerdata);
        // Not working Check what is a real problem

        echo"<pre>";
        print_r($formHandlerdata);
        exit;
        $this->curlCall($neausterFire);
    }

    public function curlCall($fieldData)
    {
        // Your curlCall logic here...
    }
}
