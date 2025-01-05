<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCourseLevelTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $level = '';
    public $status = '';
    public $created_at = '';
    function __construct() {
        parent::__construct('course_level', 'id'); // tablename, primarykey
    }

}

?>