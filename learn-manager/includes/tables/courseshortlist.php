<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCourseshortlistTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $reviews = '';
    public $student_id = '';
    public $course_id = '';
    public $created_at = '';
    
    function __construct() {
        parent::__construct('wishlist', 'id'); // tablename, primarykey
    }

}

?>