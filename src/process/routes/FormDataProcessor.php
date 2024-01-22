<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class FormDataProcessor
{
    private $fields;
    private $fieldData;
    private $commonFields;

    public function __construct($fields, $commonFields)
    {
        $this->fields = $fields;
        $this->fieldData = [];
        $this->commonFields = $commonFields;
        $this->processFormData();
    }

    public function processFormData()
    {
        if ($_SERVER['REMOTE_ADDR'] == '14.102.161.106') {
            $this->fields['state'] = 'CA';
        }

        if (isset($this->fields['current_situation']) && is_array($this->fields['current_situation'])) {
            $this->fields['current_situation'] = implode(",", $this->fields['current_situation']);
        }
        foreach ($this->fields as $field => $value) {
            if (!empty($this->fields[$field])) {
                $this->fieldData[$field] = $this->fields[$field];
                continue;
            }

            // Handle values that we want but didn't get for some reason
            switch ($field) {
                    // Handle pages that submit 'ckm_request_id' instead of 'reqid'
                case 'reqid':
                    if (!empty($this->fields['ckm_request_id'])) {
                        $this->fieldData[$field] = $this->fields['ckm_request_id'];
                    }
                    break;
                    // Add more cases as needed
            }
        }

        $this->fieldData['page'] = $_SERVER['SERVER_NAME'] . strtok($this->fields['page'], '?');
    }

    public function getFieldData()
    {
        return $this->fieldData;
    }
}
