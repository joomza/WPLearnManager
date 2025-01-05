<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERAccessTypeTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $access_type = '';
    public $country_code = '';
    public $status = '';
    public $created_at = '';
    function __construct() {
        parent::__construct('course_access_type', 'id'); // tablename, primarykey
    }

}

?>