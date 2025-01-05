<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERemailtemplatestatusModel {

    function sendEmailModel($id, $actionfor) {
        if (empty($id))
            return false;
        if (!is_numeric($actionfor))
            return false;

        $row = JSLEARNMANAGERincluder::getJSTable('emailtemplateconfig');
        $value = 1;

        switch ($actionfor) {
            case 1: 
                $row->update(array('id' => $id, 'admin' => $value));
                break;
            case 2: 
                $row->update(array('id' => $id, 'instructor' => $value));
                break;
            case 3: 
                $row->update(array('id' => $id, 'student' => $value));
                break;
        }
    }

    function noSendEmailModel($id, $actionfor) {
        if (empty($id))
            return false;
        if (!is_numeric($actionfor))
            return false;
        $row = JSLEARNMANAGERincluder::getJSTable('emailtemplateconfig');
        $value = 0;

        switch ($actionfor) {
            case 1: 
                $row->update(array('id' => $id, 'admin' => $value));
                break;
            case 2: 
                $row->update(array('id' => $id, 'instructor' => $value));
                break;
            case 3: 
                $row->update(array('id' => $id, 'student' => $value));
                break;
        }
    }

    function getEmailTemplateStatusData() {
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_emailtemplates_config`";
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObjectList();
        $newdata = array();
        foreach (jslearnmanager::$_data[0] as $data) {
            
            $newdata[$data->emailfor] = array(
                'tempid' => $data->id,
                'tempname' => $data->emailfor,
                'admin' => $data->admin,
                'instructor' => $data->instructor,
                'student' => $data->student,
                'seller_visitor' => $data->seller_visitor,
                'buyer_visitor' => $data->buyer_visitor
            );
        }
        jslearnmanager::$_data[0] = $newdata;
    }

    function getEmailTemplateStatus($template_name) {
        $db = new jslearnmanagerdb();
        if(! $template_name)
            return '';
        $query = "SELECT emc.admin,emc.instructor,emc.student
                FROM `#__js_learnmanager_emailtemplates_config` AS emc
                where  emc.emailfor = '" . $template_name . "'";
        $db->setQuery($query);
        $templatestatus = $db->loadObject();
        return $templatestatus;
    }

    function getMessagekey(){
        $key = 'emailtemplatestatus';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }
}

?>
