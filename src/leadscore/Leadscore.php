<?php

namespace BytesNirav\CakeCorePhp\leadscore;

class Leadscore
{
    private $url = 'http://leadscoreapi.px.com/px';
    private $PartnerToken;

    public function __construct()
    {
        $this->PartnerToken = '7924B009-411A-4ECE-B91D-B54828E988FB';
    }

    public function validatePhone($phoneNumber, $first_name, $last_name, $email, $state, $zipcode, $fullresponse)
    {
        $response = $this->makeRequest($phoneNumber, $first_name, $last_name, $email, $state, $zipcode);
        if ($response) {
            if ($fullresponse === false) {
                $result = $this->decodeResponse($response);
                return $this->testResult($result);
            } else {
                $result = $this->decodeResponse($response);
                $final_result = $this->testResult($result);
                $response = $result[1] . "," . $result[2] . "," . $result[3] . "," . $result[4] . "," . $result[5];

                return compact('final_result', 'response');
            }
        }
        return 0;
    }

    private function decodeResponse($response)
    {
        if (!empty($response)) {
            $result = array(
                "1" => $response['Prepaid_Phone_Indicator'],
                "2" => $response['Business_Phone_Indicator'],
                "3" => $response['Phone_In-Service_Indicator'],
                "4" => $response['Phone_Type_Indicator'],
                "5" => $response['Service_Discontinued_Indicator']
            );
            return $result;
        }
        return 0;
    }

    private function testResult($result)
    {
        if (strpos($result[3], 'A') !== false || strpos($result[3], 'U') !== false) {
            return 1;
        }
        return 0;
    }

    private function makeRequest($phoneNumber, $first_name, $last_name, $email, $state, $zipcode)
    {
        $fields = array(
            "PartnerToken" => $this->PartnerToken,
            //"Type" => "jsonwsp/request",
            "BaeReplyType" => "json",
            "Version" => "1.0",
            "MethodName" => "Lead.GetLeadScore",
            "IsRawResponse" => 2,
            "FirstName" => $first_name,
            "LastName" => $last_name,
            "Address" => "2700 Madonna Dr",
            "City" => "Fullerton",
            "State" => $state != "" ? $state : "CA",
            "ZipCode" => $zipcode != "" ? $zipcode : "92835",
            "EmailAddress" => $email,
            "PhoneNumber" => $phoneNumber,
            "Country" => "US",
            "IpAddress" => "255.255.255.255"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($fields))
            )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        $headers = curl_getinfo($ch);

        // close curl
        curl_close($ch);
        $data = json_decode($data, 1);
        $data = $data['Results'];

        // return XML data
        if ($headers['http_code'] != '200') {
            return false;
        } else {
            return ($data);
        }
    }
}
