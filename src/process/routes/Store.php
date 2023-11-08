<?php
namespace BytesNirav\CakeCorePhp\process\routes;

class Store
{
    private $commonFields;
    private $fields;
    private $DB;

    public function __construct($commonFields, $fields, $DB)
    {
        $this->commonFields = $commonFields;
        $this->fields = $fields;
        $this->DB = $DB;
    }

    public function processForm()
    {
        $this->adjustDebtAmount();
    }

    private function adjustDebtAmount()
    {
        if ($_POST['LeadRouting'] == "LONG_FORM") {
            $_POST['debt_amount'] = ($_POST['personal_loan'] == "yes") ? 10000 : 9000;
        }
    }

    public function checkField()
    {
        $requiredFields = ['debt_amount', 'email_address', 'primary_phone'];

        if ($this->areFieldsEmpty($requiredFields)) {
            $this->handleMissingFields();
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

        $phoneNumber = preg_replace('/\D+/', '', $_POST['primary_phone']);

        $arrParams = [
            'debt_amount' => $_POST['debt_amount'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email_address' => $_POST['email_address'],
            'state' => $_POST['state'],
            'primary_phone' => $phoneNumber,
            'error_post' => 1
        ];

        $arrQueryParams = array_merge($arrQueryParams, $arrParams);
        $postString = http_build_query($arrQueryParams);

        return "http://$RefererHost$RefererURI?$postString";
    }
}


// Example usage:
$formProcessor = new Store($commonFields, $fields, $DB);
$formProcessor->processForm();
