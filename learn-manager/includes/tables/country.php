<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCountryTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $country_name = '';
    public $country_code = '';
    public $isenable = '';
    public $created_at = '';
    public $updated_at = '';
    function __construct() {
        parent::__construct('country', 'id'); // tablename, primarykey
    }

}

?>