<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERInstructorTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $name = '';
    public $bio = '';
    public $image = '';
    public $gender = '';
    public $user_id = '';
    public $approvalstatus = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('instructor', 'id'); // tablename, primarykey
    }

}

?>