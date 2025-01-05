<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERupdates {

    static function checkUpdates() {
        $installedversion = JSLEARNMANAGERupdates::getInstalledVersion();
        if ($installedversion != jslearnmanager::$_currentversion) {
            $db = new jslearnmanagerdb();
            //UPDATE the last_version of the plugin
            $query = "REPLACE INTO `#__js_learnmanager_config` (`configname`, `configvalue`, `configfor`) VALUES ('last_version','','default');";
            $db->setQuery($query);
            $db->query();
            $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname='versioncode'";
            $db->setQuery($query);
            $versioncode = $db->loadResult();
            $versioncode = str_replace('.','',$versioncode);
            $query = "UPDATE `#__js_learnmanager_config` SET configvalue = '".$versioncode."' WHERE configname = 'last_version';";
            $db->setQuery($query);
            $db->query();
            $from = $installedversion + 1;
            $to = jslearnmanager::$_currentversion;
            for ($i = $from; $i <= $to; $i++) {
                $installfile = JSLEARNMANAGER_PLUGIN_PATH . 'includes/updates/sql/' . $i . '.sql';
                if (file_exists($installfile)) {
                    $delimiter = ';';
                    $file = fopen($installfile, 'r');
                    if (is_resource($file) === true) {
                        $query = array();

                        while (feof($file) === false) {
                            $query[] = fgets($file);
                            if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                $query = trim(implode('', $query));
                                if (!empty($query)) {
                                    $db->setQuery($query);
                                    $db->query();
                                }
                            }
                            if (is_string($query) === true) {
                                $query = array();
                            }
                        }
                        fclose($file);
                    }
                }
            }
        }
    }

    static function getInstalledVersion() {
        $db = new jslearnmanagerdb();
        $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname = 'versioncode'";
        $db->setQuery($query);
        $version = $db->loadResult();
        if (!$version)
            $version = '102';
        else
            $version = str_replace('.', '', $version);
        return $version;
    }

}

?>
