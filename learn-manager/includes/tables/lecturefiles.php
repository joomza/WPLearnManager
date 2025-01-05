<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERLectureFilesTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $filename = '';
    public $file_type = '';
    public $lecture_id = '';
    public $fileurl = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('lecture_file', 'id'); // tablename, primarykey
    }

}

?>