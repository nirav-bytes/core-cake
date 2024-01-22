<?php

namespace BytesNirav\CakeCorePhp\includes;

class InfutorAPI
{

    private $username;
    private $password;

    public function __construct()
    {
        $this->username = 'for86849';
        $this->password = '43SDXR3SK$MB8Ct';
    }

    public function infutorAPIRequest($processedData)
    {
        $curl = curl_init();

        $query = http_build_query(
            array(
                'Login' => $this->username,
                'Password' => $this->password,
                'FullName' => $processedData['first_name'] . " " . $processedData['last_name'],
                'FName' => $processedData['first_name'],
                'LName' => $processedData['last_name'],
                'Phone' => $processedData['primary_phone'],
                'Email' => $processedData['email_address']
            )
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.yourdatadelivery.com/IDComplete?" . $query . "&=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
