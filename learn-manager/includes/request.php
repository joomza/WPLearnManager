<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERrequest {
    /*
     * Check Request from both the Get and post method
     */

    static function getVar($variable_name, $method = null, $defaultvalue = null, $typecast = null) {
        $value = null;
        if ($method == null) {
            if (isset($_GET[$variable_name])) {
                if (is_array($_GET[$variable_name])) {
                    foreach ($_GET[$variable_name] as $val) {
                        $value[] = sanitize_key($val);
                    }
                } else {
                    $value = filter_var($_GET[$variable_name], FILTER_SANITIZE_STRING);
                }
            } elseif (isset($_POST[$variable_name])) {
                if (is_array($_POST[$variable_name])) {
                    foreach ($_POST[$variable_name] as $val) {
                        $value[] = sanitize_key($val);
                    }
                } else {
                    $value = filter_var($_POST[$variable_name], FILTER_SANITIZE_STRING);
                }
            } elseif (get_query_var($variable_name)) {
                $value = filter_var(get_query_var($variable_name, FILTER_SANITIZE_STRING));
            } elseif (isset(jslearnmanager::$_data['sanitized_args'][$variable_name]) && jslearnmanager::$_data['sanitized_args'][$variable_name] != '') {
                $value = filter_var(jslearnmanager::$_data['sanitized_args'][$variable_name], FILTER_SANITIZE_STRING);
            }
        } else {
            $method = strtolower($method);
            switch ($method) {
                case 'post':
                    if (isset($_POST[$variable_name]))
                        $value = filter_var($_POST[$variable_name], FILTER_SANITIZE_STRING);
                    break;
                case 'get':
                    if (isset($_GET[$variable_name]))
                        $value = filter_var($_GET[$variable_name], FILTER_SANITIZE_STRING);
                    break;
            }
        }
        if ($typecast != null) {
            $typecast = strtolower($typecast);
            switch ($typecast) {
                case "int":
                    $value = (int) $value;
                    break;
                case "string":
                    $value = (string) $value;
                    break;
            }
        }
        if ($value == null)
            $value = $defaultvalue;
        return $value;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function get($method = null) {
        $array = null;
        if ($method != null) {
            $method = strtolower($method);
            switch ($method) {
                case 'post':
                    $array = sanitize_post($_POST);
                    break;
                case 'get':
                    $array = sanitize_key($_GET);
                    break;
            }
        }
        return $array;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function getLayout($layout, $method, $defaultvalue) {
        $layoutname = null;
        if ($method != null) {
            $method = strtolower($method);
            switch ($method) {
                case 'post':
                    $layoutname = filter_var($_POST[$layout], FILTER_SANITIZE_STRING);
                    break;
                case 'get':
                    $layoutname = sanitize_key($_GET[$layout]);
                    break;
            }
        } else {
            if (isset($_POST[$layout]))
                $layoutname = filter_var($_POST[$layout], FILTER_SANITIZE_STRING);
            elseif (isset($_GET[$layout]))
                $layoutname = sanitize_key($_GET[$layout]);
            elseif (isset(jslearnmanager::$_data['sanitized_args'][$layout]) && jslearnmanager::$_data['sanitized_args'][$layout] != '')
                $layoutname = jslearnmanager::$_data['sanitized_args'][$layout];
            elseif (get_query_var($layout))
                $layoutname = get_query_var($layout);
        }
        if ($layoutname == null) {
            $layoutname = $defaultvalue;
        }
        if (is_admin()) {
            $layoutname = 'admin_' . $layoutname;
        }
        return $layoutname;
    }

}

?>
