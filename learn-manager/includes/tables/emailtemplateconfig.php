<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERemailtemplateconfigTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $emailfor = '';
    public $admin = '';
    public $instructor = '';
    public $student = '';
    
    function __construct() {
        parent::__construct('emailtemplates_config', 'id'); // tablename, primarykey
    }

}

?>