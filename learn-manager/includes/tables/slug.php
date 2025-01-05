<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERslugTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $slug = '';
    public $defaultslug = '';
    public $filename = '';
    public $description = '';
    public $status = '';
    

    function __construct() {
        parent::__construct('slug', 'id'); // tablename, primarykey
    }

}

?>