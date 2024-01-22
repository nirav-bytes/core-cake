<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class CheckFieldData
{
    private $commonFields;
    private $fields;
    private $DB;

    public function __construct($fields, $commonFields, $DB)
    {
        $this->commonFields = $commonFields;
        $this->fields = array_merge($commonFields, $fields);
        $this->DB = $DB;
        $this->checkField();
    }
    public function checkField()
    {
        $requiredFields = ['tax_debt', 'email_address', 'primary_phone'];

        foreach ($requiredFields as $field) {
            if (empty($this->fields[$field])) {
                $this->handleMissingFields();
                break;
            }
        }
    }

    private function areFieldsEmpty($fields)
    {
        return array_reduce($fields, function ($carry, $field) {
            return $carry || empty(trim($_POST[$field]));
        }, false);
    }

    private function handleMissingFields()
    {
        $_SERVER['HTTP_REFERER'] = "http://dev.easy-tax-relief.com/lp1.php";  //make dynamic
        if ($_SERVER['HTTP_REFERER'] !== "") {
            $redirectURL = $this->buildRedirectURL();
            header("Location: $redirectURL");
            exit;
        } else {
            header("Location: /thankyou.php");
            exit;
        }
    }

    private function buildRedirectURL()
    {
        $RefererHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        $RefererURI = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        $RefererQueryParam = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);

        $arrQueryParams = [];
        parse_str($RefererQueryParam, $arrQueryParams);

        $phoneNumber = preg_replace('/\D+/', '', $this->fields['primary_phone']);

        $arrParams = [
            'first_name' => $this->fields['first_name'],
            'last_name' => $this->fields['last_name'],
            'email_address' => $this->fields['email'],
            'primary_phone' => $phoneNumber,
            'error_post' => 1
        ];

        $arrQueryParams = array_merge($arrQueryParams, $arrParams);
        $postString = http_build_query($arrQueryParams);

        return "http://$RefererHost$RefererURI?$postString";
    }
}
