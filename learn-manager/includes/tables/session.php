<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERsessionTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $sessiondata = '';
   
    

    function __construct() {
        parent::__construct('session', 'id'); // tablename, primarykey
    }

}

?>