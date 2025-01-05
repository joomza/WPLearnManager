<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCurrencyTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $title = '';
    public $symbol = '';
    public $code = '';
    public $status = '';
    public $isdefault = '';
    public $ordering = '';

    function __construct() {
        parent::__construct('currencies', 'id'); // tablename, primarykey
    }

}

?>