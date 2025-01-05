<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERusersTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $username = '';
    public $name = '';
    public $firstname = '';
    public $lastname = '';
    public $uid = '';
    public $email = '';
    public $facebook_url = '';
    public $twitter = '';
    public $linkedin = '';
    public $country_id = '';
    public $weblink = '';
    public $user_role_id = '';
    public $status = '';
    public $socialid = '';
    public $socialmedia = '';
    public $issocial = '';
    public $params = '';
    public $created_at = '';
    public $updated_at = '';

    function __construct() {
        parent::__construct('user', 'id'); // tablename, primarykey
    }

}

?>