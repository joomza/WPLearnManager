<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERSectionLectureTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $name = '';
    public $section_id = '';
    public $description = '';
    public $lecture_order = '';
    public $params = '';
    public $alias = '';
    public $status = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('course_section_lecture', 'id'); // tablename, primarykey
    }

}

?>