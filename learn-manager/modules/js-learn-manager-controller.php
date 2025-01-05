<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class jslearnmanagerController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = JSLEARNMANAGERrequest::getVar('jslmsmod', null, 'jslearnmanager');
        JSLEARNMANAGERincluder::include_file($module);
    }

}

$jslearnmanagerController = new jslearnmanagerController();
?>