<?php

namespace BytesNirav\CakeCorePhp\process;

use BytesNirav\CakeCorePhp\includes\DB;
use PHLAK\Config\Config;

class Init
{
    private $subdomain;
    private $domain;
    private $tld;
    private $server;
    private $config;
    private $config_wp;
    private $DB;
    private $DB_WP;

    public function __construct()
    {
        session_start();
        $this->server = $_SERVER['HTTP_HOST'];
        $this->parseServer();
        $this->loadConfig();
        // $this->loadConfigWP();
        $this->initializeDB();
    }

    private function parseServer()
    {
        if (substr_count($this->server, '.') >= 2) {
            list($this->subdomain, $this->domain, $this->tld) = explode('.', $this->server);
            if ($this->server == "dev.solvable.com") {
                $this->tld = "dev";
            }
        } else {
            list($this->domain, $this->tld) = explode('.', $this->server);
        }
    }

    private function loadConfig()
    {
        $this->domain = "dev.solvable.com";
        // $configFile = "config/{$this->domain}{$this->tld}.yaml";
        $configFile = __DIR__ . "/config/stage.yaml";
        $this->config = new Config($configFile);
    }

    private function loadConfigWP()
    {
        // $configFileWP = "config/{$this->domain}-wp.{$this->tld}.yaml";
        $configFileWP = __DIR__ . "/config/stage.yaml";
        $this->config_wp = new Config($configFileWP);
    }

    private function initializeDB()
    {
        try {
            $this->DB = new DB($this->config);
            // $this->DB_WP = new DB($this->config_wp);
            return $this->DB;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getDBConnection()
    {
        return $this->DB;
    }

    public function getCommonFields()
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
            'zip_code',
            'employment_status',
            'current_situation_reason',
            'tag',
            'credit_card_debt',
            'debt_amount',
            'personal_loan',
            'birth_date',
            'full_address_street',
            'full_address_city',
            'full_address_zip_code',
            'full_address_state',
            'universal_leadid',
            'fdr_status'
        );
    }

    // Add other methods as needed

}

// Create an object of ConfigHandler
// $configHandler = new Init();
