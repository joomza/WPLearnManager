<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcoursesearchModel {

    function getCourseSearchOptions() {
        jslearnmanager::$_data[2] = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getFieldsOrderingforSearch(1);
    }

    

    function getMessagekey(){
        $key = 'coursesearch';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
