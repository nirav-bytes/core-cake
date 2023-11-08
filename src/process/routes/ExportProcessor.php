<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class ExportProcessor
{
    private $commonFields;
    private $config;
    private $DB;
    private $doExport;

    public function __construct($commonFields, $config, $DB, $doExport)
    {
        error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

        $this->commonFields = $commonFields;
        $this->config = $config;
        $this->DB = $DB;
        $this->doExport = $doExport;
    }

    public function processExport()
    {
        $fields = array_merge($this->commonFields, $this->config->get('fields'));

        $result = $this->DB->read($this->config->get('dbtable'));

        $rowCount = 0;
        if (!$this->doExport) {
            $header = "<table class=\"table table-condensed table-striped\">\n  <thead>\n    <tr>\n";
            foreach ($fields as $field) {
                $field = $this->getFieldAlias($field);
                $header .= "      <th>$field</th>\n";
            }
            $header .= "    </tr>\n  </thead>\n";

            $body = "  <tbody>\n";

            foreach ($result as $row) {
                $body .= "    <tr>\n";
                foreach ($fields as $field) {
                    switch ($field) {
                        case 'id':
                            $link = '<a href="/local_storage/push/?authKey=b7hak8w2nKDb2KS2n0d&amp;id=' . $row->$field . '" target="_blank">' . $row->$field . '</a>';
                            $body .= "      <td class=\"$field\" >{$link}</td>\n";
                            break;
                        case 'query_string':
                            $queryString = (!empty($row->$field)) ? '[Query String]' : '';
                            $body .= "      <td class=\"$field\" title=\"{$row->$field}\">" . $queryString . "</td>\n";
                            break;
                        case 'referrer':
                            $body .= "      <td class=\"$field\" title=\"{$row->$field}\">" . substr($row->$field, 0, 50) . "</td>\n";
                            break;
                        case 'submit_attempts':
                            $body .= "      <td class=\"tries\" title=\"{$row->user_agent}\">{$row->$field}</td>\n";
                            break;
                        case 'created_at':
                            $body .= "      <td class=\"$field\" title=\"" . long2ip($row->ip_address) . "\">{$row->$field}</td>\n";
                            break;
                        default:
                            $body .= "      <td class=\"$field\" >{$row->$field}</td>\n";
                    }
                }
                $body .= "    </tr>\n";
                $rowCount++;
            }
            $body .= "  </tbody>\n</table>\n";

            $body .= "<h3>Entries: $rowCount</h3>\n";

            require_once('./includes/page_template.php');
            return $body;
        }
    }

    private function getFieldAlias($field)
    {
        switch ($field) {
            case 'ckm_offer_id':
                return 'ckm';
            case 'enrolled_irs':
                return 'irs';
            case 'submit_attempts':
                return 'tries';
            default:
                return $field;
        }
    }
}

// Example usage:
$exportProcessor = new ExportProcessor($commonFields, $config, $DB, $doExport);
$resultHTML = $exportProcessor->processExport();
echo $resultHTML;  // Output the HTML result
