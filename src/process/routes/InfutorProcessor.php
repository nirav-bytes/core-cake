<?php

namespace BytesNirav\CakeCorePhp\process\routes;

use BytesNirav\CakeCorePhp\includes\InfutorAPI;
use SimpleXMLElement;

class InfutorProcessor
{
    // private $processedData;

    public function processInfutorResponse($processedData)
    {
        $infuterClass = new InfutorAPI();
        $Infutor_responce = $infuterClass->infutorAPIRequest($processedData);
        $xml = new SimpleXMLElement($Infutor_responce);
        $Infutor_responce_array = json_decode(json_encode($xml), true);

        // Your conditions from the original code
        if (!empty($Infutor_responce_array['Response']['Detail']['Identity']['Address']['State'])) {
            $processedData['infutor_state'] = $Infutor_responce_array['Response']['Detail']['Identity']['Address']['State'];
        }

        if (!empty($Infutor_responce_array['Response']['Detail']['Identity']['Address']['City'])) {
            $processedData['infutor_city'] = $Infutor_responce_array['Response']['Detail']['Identity']['Address']['City'];
        }

        if (!empty($Infutor_responce_array['Response']['Detail']['Identity']['PreviousAddress1'])) {
            $processedData['infutor_address1'] = $Infutor_responce_array['Response']['Detail']['Identity']['PreviousAddress1'];
        }

        if (!empty($Infutor_responce_array['Response']['Detail']['Identity']['PreviousAddress2'])) {
            $processedData['infutor_address2'] = $Infutor_responce_array['Response']['Detail']['Identity']['PreviousAddress2'];
        }

        if (!empty($Infutor_responce_array['Response']['Detail']['Identity']['Address']['Zip'])) {
            $processedData['infutor_zip'] = @$Infutor_responce_array['Response']['Detail']['Identity']['Address']['Zip'];
        }

        $Infutor_responce = json_encode($Infutor_responce_array);

        if (!empty($Infutor_responce_array['Response']['Detail']['IDScores']['NameToPhone'])) {
            $processedData['infutor_nametophone'] = @$Infutor_responce_array['Response']['Detail']['IDScores']['NameToPhone'];
        }
        if (!empty($Infutor_responce_array['Response']['Detail']['IDScores']['ValidationSummary'])) {
            $processedData['infutor_validation_summury'] = @$Infutor_responce_array['Response']['Detail']['IDScores']['ValidationSummary'];
        }

        $processedData['infutor_status'] = "pass";

        if (!empty($processedData['infutor_validation_summury']) && !empty($processedData['infutor_nametophone']) && (strtoupper($processedData['infutor_validation_summury']) == "INCONCLUSIVE" ||  strtoupper($processedData['infutor_validation_summury']) == "FAIL") && $processedData['infutor_nametophone'] == "1") {
            $processedData['infutor_status'] = "fail";
        }

        if (isset($processedData['infutor_zip']) && !empty($processedData['infutor_zip'])) {
            $processedData['zip_pass'] = "yes";
        }

        if ($processedData['tax_debt'] < 9999) {
            if (!empty($processedData['infutor_validation_summury']) && !empty($processedData['infutor_nametophone']) && (strtoupper($processedData['infutor_validation_summury']) =="INCONCLUSIVE" ||  strtoupper($processedData['infutor_validation_summury']) =="FAIL") && $processedData['infutor_nametophone'] == "1") {
                $processedData['infutor_under']= "fail";
            } else {
                $fieldData['infutor_under'] = "pass";
            }
        }

        return $processedData;
    }
}

// Rest of the classes and methods...
