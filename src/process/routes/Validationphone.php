<?php

namespace BytesNirav\CakeCorePhp\process\routes;

use BytesNirav\CakeCorePhp\leadscore\Leadscore;
use BytesNirav\CakeCorePhp\leadscore\Leadscore2;

class Validationphone
{
    public function validatePhoneNumber($processedData)
    {
        $leadscored = new Leadscore();
        if ($processedData['tax_debt'] > 9999) {
            $phoneNumber = preg_replace('/\D+/', '', $processedData['primary_phone']);
            if (strlen($phoneNumber) == 10) {
                $check = $leadscored->validatePhone($processedData['primary_phone'], $processedData['first_name'], $processedData['last_name'], $processedData['email_address'], $processedData['state'], $processedData['zipcode'], true);

                if ($check != 0) {
                    $processedData['neustar_disposition'] = $check['response'];
                }
            }
        }

        if ($processedData['tax_debt'] > 9999) {
            if (!isset($processedData['zipcode'])) {
                $processedData['zipcode'] = "";
            }
            $nd_odlv = "";
            $leadscored2 = new Leadscore2();
            $phoneNumber = preg_replace('/\D+/', '', $processedData['primary_phone']);
            if (strlen($phoneNumber) == 10) {
                $check = $leadscored2->validatePhone($processedData['primary_phone'], $processedData['first_name'], $processedData['last_name'], $processedData['email_address'], $processedData['state'], $processedData['zipcode'], true);
                if ($check != 0) {
                    $nd_odlv = $check['response'];
                }
            }
            $processedData['neustar'] = 'pass';

            if (isset($processedData['neustar_disposition']) && trim($processedData['neustar_disposition'], ",") != "") {
                $processedData['neustar'] = 'fail';
                $ns_disposition = $processedData['neustar_disposition'];
                if (
                    strpos($ns_disposition, "I") !== FALSE ||
                    strpos($ns_disposition, ",1") !== FALSE ||
                    strpos($ns_disposition, ",2") !== FALSE ||
                    strpos($ns_disposition, ",3") !== FALSE ||
                    strpos($ns_disposition, ",4") !== FALSE ||
                    $nd_odlv == 26 || $nd_odlv == 31
                ) {
                    $processedData['neustar'] = 'fail';
                } else {
                    $processedData['neustar'] = 'pass';
                }
            } else if (isset($processedData['neustar_disposition']) && $processedData['neustar_disposition'] == ",,,,") {
                $processedData['neustar'] = 'fail';
            }
        }
        return ['processedData' => $processedData, 'nd_odlv' => $nd_odlv];
    }
}
