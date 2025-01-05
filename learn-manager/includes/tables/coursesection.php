<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCourseSectionTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $name = '';
    public $course_id = '';
    public $access_type = '';
    public $alias = '';
    public $section_order = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('course_section', 'id'); // tablename, primarykey
    }

}

?>