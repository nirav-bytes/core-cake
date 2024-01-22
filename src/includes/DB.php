<?php

namespace BytesNirav\CakeCorePhp\includes;

use DateTime;
use PDO;

/**
 * Created by Bill Wheeler
 *
 * REALLY Basic PDO Wrapper just to make some things easier.
 * Date: 6/8/16
 * Time: 11:03 AM
 */
class DB
{

    public $dbm;

    public $insertID;

    public function __construct($config)
    {
        $config = $config->get('drivers.mysql');
        $dbSource = 'mysql:host=localhost;dbname=' . $config['database'];
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        $this->dbm = new PDO($dbSource, $config['username'], $config['password'], $options);
        $this->dbm->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert($table, $fieldData)
    {
        $submitted = new DateTime();
        $fieldData['created_at'] = $submitted->format('Y-m-d H:i:s');

        $fields = implode(', ', array_keys($fieldData));
        $data = implode(', ', $this->quote(array_values($fieldData)));

        $sql = "INSERT INTO $table ( $fields ) VALUES ( $data );";

        $result = $this->dbm->exec($sql);
        $this->insertID = $this->dbm->lastInsertId();

        if ($result === false) return false;
        return $result;
    }

    public function update($table, $fieldData, $where)
    {
        $updateData = $rawData = array();
        foreach ($fieldData as $field => $value) {
            if ($field == 'RAW') {
                if (stripos($value, "sleep") !== FALSE || stripos($value, "SLEEP") !== FALSE  || stripos($value, "TRUNCATE") !== FALSE || stripos($value, "DROP") !== FALSE || stripos($value, "ALTER") !== FALSE) {
                    continue;
                }
                $rawData[] = $value;
                continue;
            }
            $updateData[] = $field . ' = ' . $this->dbm->quote($value);
        }

        $rawString = "";
        $updateString = implode(', ', $updateData);
        if (!empty($rawData)) {
            $rawString = implode(', ', $rawData);
        }

        if ($rawString != "")
            $sql = "UPDATE $table SET $updateString, $rawString WHERE $where";
        else
            $sql = "UPDATE $table SET $updateString WHERE $where";
        $result = $this->dbm->exec($sql);

        if ($result === false) return false;
        return $result;
    }

    public function read($table, $where = '', $order = 'created_at DESC')
    {
        $sql = "SELECT * FROM {$table} {$where} ORDER BY {$order}";

        $stmt = $this->dbm->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    private function quote($data)
    {
        $quotedData = [];
        foreach ($data as $value) {
            $quotedData[] = $this->dbm->quote($value);
        }
        return $quotedData;
    }
}
