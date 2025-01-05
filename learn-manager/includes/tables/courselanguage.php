<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCourseLanguageTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $language = '';
    public $status = '';
    public $created_at = '';
    function __construct() {
        parent::__construct('language', 'id'); // tablename, primarykey
    }

}

?>