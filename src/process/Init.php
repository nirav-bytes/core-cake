<?php

namespace BytesNirav\CakeCorePhp\process;

use BytesNirav\CakeCorePhp\includes\DB;
use BytesNirav\CakeCorePhp\process\routes\Store;
use PHLAK\Config\Config;

class Init
{
    private $subdomain;
    private $domain;
    private $tld;
    private $server;
    private $configFile;
    private $config;
    private $DB;

    public function __construct()
    {
        session_start();

        $this->initializeServerInfo();
        $this->initializeConfig();
        $this->initializeDatabase();
    }

    private function initializeServerInfo()
    {
        $this->server = 'dev.easy-tax-relief.com';
        if (substr_count($this->server, '.') >= 2) {
            list($this->subdomain, $this->domain, $this->tld) = explode('.', $this->server);
            if ($this->server == "dev.easy-tax-relief.com") {
                $this->tld = "dev";
            }
        } else {
            list($this->domain, $this->tld) = explode('.', $this->server);
        }
    }

    private function initializeConfig()
    {
        $this->configFile = __DIR__ . "/config/{$this->tld}.yaml";
        $this->config = new Config($this->configFile);
    }

    public function initializeDatabase()
    {
        $db = new DB($this->config);
        return $db;
    }

    public function processFields()
    {
        return array(
            'affid',
            'ckm_offer_id',
            'oc',
            'reqid',
            'page',
            'query_string',
            's1',
            's2',
            's3',
            's4',
            's5',
            'subid',
            'referrer',
            'neustar',
            'melissa',
            'opt_in',
            'current_situation',
            'income',
            'zipcode',
            'employment_status',
            'current_situation_reason',
            'tag',
            'credit_card_debt',
            'debt_amount',
            'personal_loan',
            'birth_date',
            'address',
            'full_address_street',
            'full_address_city',
            'full_address_zip_code',
            'full_address_state',
            'universal_leadid',
            'fdr_status'
        );
    }

}
