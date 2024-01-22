<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class UniversalLeadid
{
    public static function authuniversalLeadid($processedData)
    {
        if (!empty($processedData['universal_leadid'])) {
            $processedData['jornaya_leadid'] = $processedData['universal_leadid'];

            $data = "f_name;" . $processedData['first_name'] . "|l_name;" . $processedData['last_name'] . "|phone1;" . $processedData['primary_phone'] . "|email;" . $processedData['email_address'];
            $ch = curl_init('https://api.leadid.com/SingleQuery?lac=581E5A37-7A2C-A742-C313-6F515B2D3222&id=' . $processedData['universal_leadid'] . '&lak=DC38B41E-20A2-558B-9AF0-44E6A69452CB&lpc=03D25297-91B2-7DEA-48A9-88CEE9696E12&data=' . $data . '&format=json');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $result = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($result, true);

            if (!isset($responseData['audit']['authentic']) || $responseData['audit']['authentic'] != 1) {
                header("Location:thankyou.php");
                exit;
            }
        }

    }
}
