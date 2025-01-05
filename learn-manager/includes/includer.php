<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERincluder {

    function __construct() {

    }

    /*
     * Includes files
     */

    public static function include_file($filename, $module_name = null) {
        if ($module_name != null) {
            $file_path = JSLEARNMANAGERincluder::getPluginPath($module_name,'file',$filename);
            if(is_array($file_path) && file_exists($file_path['tmpl_file'])){
                if (file_exists($file_path['inc_file'])) {
                    require_once($file_path['inc_file']);
                }
                include_once $file_path['tmpl_file'];
            }else if(file_exists($file_path)){
                $incfilepath = explode('.php', $file_path);
                $incfilename = $incfilepath[0].'.inc.php';
                if (file_exists($incfilename)) {
                    require_once($incfilename);
                }
                include_once $file_path; //
            }else{
                $file_path = JSLEARNMANAGERincluder::getPluginPath('premiumplugin','file','missingaddon');
                if(is_array($file_path)){
                    include_once $file_path['tmpl_file'];
                }else{
                    include_once $file_path; //
                }
            }

        } else {
            $file_path = JSLEARNMANAGERincluder::getPluginPath($filename,'file');
            if(file_exists($file_path)){
                include_once $file_path; //
            }else{
                $file_path = JSLEARNMANAGERincluder::getPluginPath('premiumplugin','file');
                include_once $file_path; //
            }
        }
        return;
    }

    /*
     * Static function to handle the page slugs
     */

    public static function include_slug($page_slug) {
        include_once JSLEARNMANAGER_PLUGIN_PATH . 'modules/learn-manager-controller.php';
    }

    /*
     * Static function for the model object
     */

    public static function getJSModel($modelname) {
        $file_path = JSLEARNMANAGERincluder::getPluginPath($modelname,'model');
        include_once $file_path;
        $classname = "JSLEARNMANAGER" . $modelname . 'Model';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes objects
     */

    public static function getObjectClass($classname) {
        $file_path = JSLEARNMANAGERincluder::getPluginPath($classname,'class');
        include_once $file_path;
        $classname = "JSLEARNMANAGER" . $classname ;
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes not objects
     */

    public static function getClassesInclude($classname) {
        $file_path = JSLEARNMANAGERincluder::getPluginPath($classname,'class');
        include_once $file_path;
    }

    /*
     * Static function for the table object
     */

    public static function getJSTable($tableclass) {
        $file_path = JSLEARNMANAGERincluder::getPluginPath($tableclass,'table');
        require_once JSLEARNMANAGER_PLUGIN_PATH .'includes/tables/table.php';
        include_once $file_path;
        $classname = "JSLEARNMANAGER" . $tableclass . 'Table';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the controller object
     */

    public static function getJSController($controllername) {
        $file_path = JSLEARNMANAGERincluder::getPluginPath($controllername,'controller');

        include_once $file_path;
        $classname = "JSLEARNMANAGER".$controllername . "Controller";
        $obj = new $classname();
        return $obj;
    }

    public static function getPluginPath($module,$type,$file_name = '') {

        $addons_secondry = array('facebook','linkedin','google','xing','awardsaction','earning','paymentmethodconfiguration','purchase','purchasehistory');

        if(in_array($module, jslearnmanager::$_active_addons)){
            $path = WP_PLUGIN_DIR.'/'.'learn-manager-'.$module.'/';
            switch ($type) {
                case 'file':
                    if($file_name != ''){
                        if (locate_template('learn-manager/' . $module . '-' . $file_name . '.php', 0, 1)) {
                            $file_path['inc_file'] = $path . 'module/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = locate_template('learn-manager/' . $module . '-' . $file_name . '.php', 0, 1);
                        }else{
                            $file_path = $path . 'module/tmpl/' . $file_name . '.php';
                        }
                    }else{
                        $file_path = $path . 'module/controller.php';
                    }
                    break;
                case 'model':
                    $file_path = $path . 'module/model.php';
                    break;

                case 'class':
                    $file_path = $path . 'classes/' . $module . '.php';
                    break;
                case 'controller':
                    $file_path = $path . 'module/controller.php';
                    break;
                case 'table':
                    $file_path = $path . 'includes/' . $module . '-table.php';
                    break;
            }

        }elseif(in_array($module, $addons_secondry)){ // to handle the case of modules that are submodules for some addon
            $parent_module = '';
            switch ($module) {// to identify addon for submodules.
                case 'facebook':
                case 'linkedin':
                case 'google':
                case 'xing':
                    $parent_module = 'sociallogin';
                break;
                case 'awardsaction':
                    $parent_module = 'awards';
                break;
                case 'earning':
                case 'paymentmethodconfiguration':
                case 'purchase':
                case 'purchasehistory':
                    $parent_module = 'paidcourse';
                break;
            }

            $path = WP_PLUGIN_DIR.'/'.'learn-manager-'.$parent_module.'/';
            if(in_array($parent_module, jslearnmanager::$_active_addons)){
                switch ($type) {
                    case 'file':
                        if($file_name != ''){
                            if (locate_template('learn-manager/' . $module . '-' . $file_name . '.php', 0, 1)) {
                                $file_path['inc_file'] = $path . $module.'/tmpl/' . $file_name . '.inc.php';
                                $file_path['tmpl_file'] = locate_template('learn-manager/' . $module . '-' . $file_name . '.php', 0, 1);
                            }else{
                                $file_path = $path . $module.'/tmpl/' . $file_name . '.php';
                            }
                        }else{
                            $file_path = $path . $module.'/controller.php';
                        }
                        break;
                    case 'model':
                        $file_path = $path . $module.'/model.php';
                        break;

                    case 'class':
                        $file_path = $path . 'classes/' . $module . '.php';
                        break;
                    case 'controller':
                        $file_path = $path . $module.'/controller.php';
                        break;
                    case 'table':
                        $file_path = $path . 'includes/' . $module . '-table.php';
                        break;
                }
            }else{
                $file_path = JSLEARNMANAGERincluder::getPluginPath('premiumplugin','file');
            }

        }else{
            $path = jslearnmanager::$_path;
            switch ($type) {
                case 'file':
                    if($file_name != ''){
                        if (locate_template('learn-manager/' . $module . '-' . $file_name . '.php', 0, 1)) {
                            $file_path['inc_file'] = $path . 'modules/' . $module . '/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = locate_template('learn-manager/' . $module . '-' . $file_name . '.php', 0, 1);
                        }else{
                            $file_path = $path . 'modules/' . $module . '/tmpl/' . $file_name . '.php';
                        }
                    }else{
                        $file_path = $path . 'modules/' . $module . '/controller.php';
                    }
                    break;
                case 'model':
                        $file_path = $path . 'modules/' . $module . '/model.php';
                    break;

                case 'class':
                    $file_path = $path . 'includes/classes/' . $module . '.php';
                    break;
                case 'controller':
                        $file_path = $path . 'modules/' . $module . '/controller.php';
                    break;
                case 'table':
                    $file_path = $path . 'includes/tables/' . $module . '.php';;
                    break;
            }
        }
        return $file_path;
    }

}

$includer = new JSLEARNMANAGERincluder();

?>
