<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERpaymentmethodconfigTable extends JSLEARNMANAGERtable {

    public $configname = '';
    public $configvalue = '';
    public $configfor = '';

    function __construct() {
        parent::__construct('paymentmethodconfig', 'id'); // tablename, primarykey
    }

}

?>