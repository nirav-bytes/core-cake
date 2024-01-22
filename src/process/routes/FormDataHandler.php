<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class FormDataHandler
{
    private $processedData;
    private $nd_odlv;

    public function __construct($processedData, $nd_odlv)
    {
        $this->processedData = $processedData;
        $this->nd_odlv = $nd_odlv;
    }

    public function processFormData()
    {
        $this->processOptSpecialOffers();
        $this->processOptIn();
        $this->processOtherFields();

        return $this->processedData;
    }

    private function processOptSpecialOffers()
    {
        if (isset($processedData['opt_special_offers']) && $processedData['opt_special_offers'] == "checked") {
            $this->processedData['email_optin_offers'] = 'checked';
        }
    }

    private function processOptIn()
    {
        if (isset($processedData['opt_in']) && ($processedData['opt_in'] == "checked" || $processedData['opt_in'] == "on")) {
            $this->processedData['opt_in'] = 'checked';

            if (!isset($processedData['opt_special_offers'])) {
                $this->processedData['email_optin_offers'] = 'checked';
            }
        }

        // Additional processing for opt_in if needed
    }

    private function processOtherFields()
    {
        // Process other fields here

        $this->processedData['opt_in'] = 'checked';
        $this->processedData['TCPA_checkbox'] = 'checked';
        $this->processedData['zip_code'] = $this->processedData['zipcode'];

        // Example for user agent, IP, page, and query processing
        $this->processedData['user_agent'] = $_SERVER["HTTP_USER_AGENT"];
        $this->processedData['ip_address'] = ip2long($_SERVER["REMOTE_ADDR"]);
        // $this->processedData['page'] = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        // $this->processedData['query_string'] = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);

        // Processing for neustar and infutor
        if ($this->processedData['neustar'] == 'fail' && $this->processedData['infutor_status'] == 'fail') {
            $this->processedData['neustar_infutor_score'] = 'fail';
        } else {
            $this->processedData['neustar_infutor_score'] = 'pass';
        }

        // Processing for nodl_flag
        $this->processedData['nodl_flag'] = 'pass';
        if ((!empty($this->nd_odlv) && ($this->nd_odlv == 11 || $this->nd_odlv == 30 || $this->nd_odlv == 3 || $this->nd_odlv == 28)) && $this->processedData['neustar'] == 'fail') {
            $this->processedData['nodl_flag'] = 'fail';
        }
    }
}
