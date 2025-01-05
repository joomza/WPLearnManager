<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERUserRoleTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $role = '';
    public $status = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('user_role', 'id'); // tablename, primarykey
    }

}

?>