<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERconfigTable extends JSLEARNMANAGERtable {

    public $configname = '';
    public $configvalue = '';
    public $configfor = '';
    public $addon = '';

    function __construct() {
        parent::__construct('config', 'configname'); // tablename, primarykey
    }

}

?>