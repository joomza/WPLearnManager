<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERStudentEnrollmentTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $student_id = '';
    public $course_id = '';
    public $lecture_completion_params = '';
    public $quiz_result_params = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('student_enrollment', 'id'); // tablename, primarykey
    }

}

?>