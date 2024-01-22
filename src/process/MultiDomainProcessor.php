<?php

namespace BytesNirav\CakeCorePhp\process;

use BytesNirav\CakeCorePhp\process\routes\Store;

class MultiDomainProcessor
{
    private $init;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        $data= [];
        $data['first_name'] = "ckmtestpixel";
        $data['last_name'] = "ckmtestpixel";
        $data['tax_debt'] = 235000;
        $data['email_address'] = "ckmtestpixel@gmail.com";
        $data["primary_phone"] = "(319) 219-6369";
        $data["state"] = "FA";
        $data["current_situation"] = "current_situation";
        $data['page'] = "";
        $data['address'] = "asdf";
        $data['zipcode'] ="12347";
        $data['universal_leadid'] = "admin";
        $this->init = new Init(); // Use the correct namespace for Init
        $dbConnection = $this->init->initializeDatabase();
        $CommonField = $this->init->processFields();
        $store = new Store($data,$CommonField,$dbConnection);
    }
}
