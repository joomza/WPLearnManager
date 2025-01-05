<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERCategoryTable extends JSLEARNMANAGERtable {

    public $id = '';
    public $category_name = '';
    public $category_img = '';
    public $alias = '';
    public $parentid = '';
    public $ordering = '';
    public $isdefault = '';
    public $status = '';
    public $created_at = '';
    public $updated_at = '';
    
    function __construct() {
        parent::__construct('category', 'id'); // tablename, primarykey
    }

}

?>
