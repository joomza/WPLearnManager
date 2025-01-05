<?php

/**
 * @package WP Learn Manager
 * @author Ahmad Bilal
 * @version 1.1.8
 */
/*
  Plugin Name: WP Learn Manager
  Plugin URI: https://www.wplearnmanager.com
  Description: Learn Manager is Word Press most comprehensive and easist show room plugin.
  Author: WP Learn Manager
  Version: 1.1.8
  Text Domain: learn-manager
  Author URI: https://www.wplearnmanager.com
 */
if (!defined('ABSPATH'))
    die('Restricted Access');

class jslearnmanager {

    public static $_path;
    public static $_pluginpath;
    public static $_data; /* data[0] for list , data[1] for total paginition ,data[2] fieldsorderring */
    public static $_pageid;
    public static $_config;
    public static $_sorton;
    public static $_sortorder;
    public static $_ordering;
    public static $_msg;
    public static $_error_flag;
    public static $_error_flag_message;
    public static $_js_login_redirct_link;
    public static $_learn_manager_theme;
    public static $_db;
    public static $_currentversion;
    public static $_active_addons;
    public static $_addon_query;
    public static $_jslmsession;
    public static $_wpprefixforuser;

    function __construct() {
        // to check what addons are active and create an array.
        $plugin_array = get_option('active_plugins');
        $addon_array = array();
        foreach ($plugin_array as $key => $value) {
            $plugin_name = pathinfo($value, PATHINFO_FILENAME);
            if(strstr($plugin_name, 'learn-manager-')){
                $addon_array[] = str_replace('learn-manager-', '', $plugin_name);
            }
        }
        self::$_active_addons = $addon_array;
        // above code is its right place

        self::includes();
        self::$_path = plugin_dir_path(__FILE__);
        self::$_pluginpath = plugins_url('/', __FILE__);
        self::$_data = array();
        self::$_msg = null;
        self::$_error_flag = null;
        self::$_error_flag_message = null;
        self::$_learn_manager_theme = 0;
        self::$_jslmsession = JSLEARNMANAGERincluder::getObjectClass('wplmssession');
        global $wpdb;
        self::$_db = $wpdb;
        self::$_currentversion = 118;
        self::$_addon_query = array('select'=>'','join'=>'','where'=>'');
        if(is_multisite()) {
            self::$_wpprefixforuser = $wpdb->base_prefix;
        }else{
            self::$_wpprefixforuser = self::$_db->prefix;
        }

        JSLEARNMANAGERincluder::getJSModel('configuration')->getConfiguration();
        register_activation_hook(__FILE__, array($this, 'jslearnmanager_activate'));
        register_deactivation_hook(__FILE__, array($this, 'jslearnmanager_deactivate'));
        if(version_compare(get_bloginfo('version'),'5.1', '>=')){ //for wp version >= 5.1
            add_action('wp_insert_site', array($this, 'jslearnmanager_new_site')); //when new site is added in multisite
        }else{ //for wp version < 5.1
            add_action('wpmu_new_blog', array($this, 'jslearnmanager_new_blog'), 10, 6);
        }
        add_filter('wpmu_drop_tables', array($this, 'jslearnmanager_delete_site')); //when site is deleted in multisite
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_action('admin_init', array($this, 'jslearnmanager_activation_redirect'));//for post installation screens
        add_action('reset_jslmaddon_query', array($this,'reset_jslm_aadon_query') );
        add_action('admin_init', array($this,'jslearnmanager_handle_search_form_data'));
        add_action('admin_init', array($this,'jslearnmanager_handle_delete_cookies'));
        add_action('init', array($this,'jslearnmanager_handle_search_form_data'));
        add_action('jslearnmanager_cronjobs_action', array($this,'jslearnmanager_cronjobs'));
        add_action( 'jslm_delete_expire_session_data', array($this , 'jslm_delete_expire_session_data') );
        add_action( 'jslm_load_file_path', array($this , 'jslm_load_file_path') );
        if( !wp_next_scheduled( 'jslm_delete_expire_session_data' )) {
            // Schedule the event
            wp_schedule_event( time(), 'daily', 'jslm_delete_expire_session_data' );
        }

        $theme = wp_get_theme();
        if($theme == 'JS Learn Manager'){
            self::$_learn_manager_theme = 1;
        }
        else{
            define( 'LEARN_MANAGER_IMAGE', self::$_pluginpath . 'includes/images' );
        }
     }
    function jslearnmanager_activation_redirect(){
        if (get_option('jslearnmanager_do_activation_redirect') == true) {
            update_option('jslearnmanager_do_activation_redirect',false);
            exit(wp_redirect(admin_url('admin.php?page=jslm_postinstallation&jslmslay=stepone')));
        }
    }

    // function jslearnmanager_cronjobs(){

    // }

    function jslearnmanager_handle_search_form_data(){
        $isadmin = is_admin();
        $jslmslay = '';
        if(isset($_REQUEST['jslmslay'])){
            $jslmslay = filter_var($_REQUEST['jslmslay'], FILTER_SANITIZE_STRING);
        }elseif(isset($_REQUEST['page'])){
            $jslmslay = filter_var($_REQUEST['page'], FILTER_SANITIZE_STRING);
        }elseif(isset($_REQUEST['jslmslt'])){
            $jslmslay = filter_var($_REQUEST['jslmslt'], FILTER_SANITIZE_STRING);
        }

        $callfrom = 3;
        if(isset($_REQUEST['JSLEANRMANAGER_form_search']) && $_REQUEST['JSLEANRMANAGER_form_search'] == 'JSLEARNMANGER_SEARCH'){
            $callfrom = 1;
        }elseif(JSLEARNMANAGERrequest::getVar('pagenum', 'get', null) != null){
            $callfrom = 2;

        }
        $setcookies = false;
        $search_cookie_data = '';
        $jslms_search_array = array();
        // print_r($jslmslay);
        // die();
        switch($jslmslay){
           case 'activitylogs':
                if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('activitylog')->getAdminActivitySearchFormData();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = filter_var($_COOKIE['jslms_search_data'], FILTER_SANITIZE_STRING);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_activitylog'])){
                        $jslms_search_array['sorton'] = $search_cookie_data['sorton'];
                        $jslms_search_array['sortby'] = $search_cookie_data['sortby'];
                    }
                }
                // earning
                jslearnmanager::$_data['filter']['sorton'] = isset($jslms_search_array['sorton']) ? $jslms_search_array['sorton'] : null;
                jslearnmanager::$_data['filter']['sortby'] = isset($jslms_search_array['sortby']) ? $jslms_search_array['sortby'] : null;
            break;
            case 'jslm_slug':
                $slug = (is_admin()) ? 'slug' : 'jslm_slug';
                $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('slug')->getAdminSlugSearchFormData();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = filter_var($_COOKIE['jslms_search_data'], FILTER_SANITIZE_STRING);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_slug'])){
                        $jslms_search_array['slug'] = $search_cookie_data['slug'];
                    }
                }
                // earning
                jslearnmanager::$_data['filter']['slug'] = isset($jslms_search_array['slug']) ? $jslms_search_array['slug'] : null;
            break;
            case 'jslm_currency':
                $title = (is_admin()) ? 'title' : 'jslm_currency';
                $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('currency')->getAdminCurrencySearchFormData();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_currency'])){
                        $jslms_search_array['title'] = $search_cookie_data['title'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                    }
                }
                // earning
                jslearnmanager::$_data['filter']['title'] = isset($jslms_search_array['title']) ? $jslms_search_array['title'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
            break;
            case 'instructorsforreports':
            case 'studentsforreports':
                $instructorname = (is_admin()) ? 'instructorname' : 'instructorsforreports';
                $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                if($callfrom == 1){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('reports')->getAdminReportsSearchFormData();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_reports'])){
                        $jslms_search_array['instructorname'] = $search_cookie_data['instructorname'];
                        $jslms_search_array['studentname'] = $search_cookie_data['studentname'];
                    }
                }
                // earning
                jslearnmanager::$_data['filter']['instructorname'] = isset($jslms_search_array['instructorname']) ? $jslms_search_array['instructorname'] : null;
                jslearnmanager::$_data['filter']['studentname'] = isset($jslms_search_array['studentname']) ? $jslms_search_array['studentname'] : null;
            break;
            case 'jslm_payouts':
                $from = (is_admin()) ? 'from' : 'jslm_payouts';
                $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('payouts')->getAdminPayoutsSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_payouts'])){
                        $jslms_search_array['from'] = $search_cookie_data['from'];
                        $jslms_search_array['to'] = $search_cookie_data['to'];
                    }
                }
                // earning
                jslearnmanager::$_data['filter']['from'] = isset($jslms_search_array['from']) ? $jslms_search_array['from'] : null;
                jslearnmanager::$_data['filter']['to'] = isset($jslms_search_array['to']) ? $jslms_search_array['to'] : null;
                jslearnmanager::$_data['filter']['payoutfrom'] = isset($jslms_search_array['payoutfrom']) ? $jslms_search_array['payoutfrom'] : null;
                jslearnmanager::$_data['filter']['payoutto'] = isset($jslms_search_array['payoutto']) ? $jslms_search_array['payoutto'] : null;
                break;


            case 'jslm_paymentplan':
             $searchname = (is_admin()) ? 'searchname' : 'jslm_paymentplan';
             $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                 if($callfrom == 1){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('paymentplan')->getAdminPaymentplanSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_paymentplan'])){
                        $jslms_search_array['searchname'] = $search_cookie_data['searchname'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                    }
                }else{
                    jslearnmanager::removeusersearchcookies();
                }
                // earning
                jslearnmanager::$_data['filter']['searchname'] = isset($jslms_search_array['searchname']) ? $jslms_search_array['searchname'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                break;

            case 'jslm_earning':
             $from = (is_admin()) ? 'from' : 'jslm_earning';
             $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('earning')->getAdminEarningSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_earning'])){
                        $jslms_search_array['from'] = $search_cookie_data['from'];
                        $jslms_search_array['to'] = $search_cookie_data['to'];
                        $jslms_search_array['course'] = $search_cookie_data['course'];
                        $jslms_search_array['instructor'] = $search_cookie_data['instructor'];
                    }
                }
                // earning
                jslearnmanager::$_data['filter']['from'] = isset($jslms_search_array['from']) ? $jslms_search_array['from'] : null;
                jslearnmanager::$_data['filter']['instructor'] = isset($jslms_search_array['instructor']) ? $jslms_search_array['instructor'] : null;
                jslearnmanager::$_data['filter']['to'] = isset($jslms_search_array['to']) ? $jslms_search_array['to'] : null;
                jslearnmanager::$_data['filter']['course'] = isset($jslms_search_array['course']) ? $jslms_search_array['course'] : null;
                break;

             case 'jslm_message':
             case 'shortmessage';
             $student = (is_admin()) ? 'student' : 'jslm_message';
             $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('message')->getAdminMessageSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_message'])){
                        $jslms_search_array['student'] = $search_cookie_data['student'];
                        $jslms_search_array['instructor'] = $search_cookie_data['instructor'];
                        $jslms_search_array['read'] = $search_cookie_data['read'];
                        $jslms_search_array['searchsubject'] = $search_cookie_data['searchsubject'];
                        $jslms_search_array['conflicted'] = $search_cookie_data['conflicted'];

                    }
                }
                // Category
                jslearnmanager::$_data['filter']['student'] = isset($jslms_search_array['student']) ? $jslms_search_array['student'] : null;
                jslearnmanager::$_data['filter']['instructor'] = isset($jslms_search_array['instructor']) ? $jslms_search_array['instructor'] : null;
                jslearnmanager::$_data['filter']['read'] = isset($jslms_search_array['read']) ? $jslms_search_array['read'] : null;
                jslearnmanager::$_data['filter']['searchsubject'] = isset($jslms_search_array['searchsubject']) ? $jslms_search_array['searchsubject'] : null;
                jslearnmanager::$_data['filter']['conflicted'] = isset($jslms_search_array['conflicted']) ? $jslms_search_array['conflicted'] : null;
                break;



            case 'jslm_awards':
             $title = (is_admin()) ? 'title' : 'jslm_awards';
             $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                 if($callfrom == 1){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('awards')->getAdminAwardSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_award'])){
                        $jslms_search_array['title'] = $search_cookie_data['title'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                        $jslms_search_array['appearence'] = $search_cookie_data['appearence'];

                    }
                }
                // Category
                jslearnmanager::$_data['filter']['title'] = isset($jslms_search_array['title']) ? $jslms_search_array['title'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                jslearnmanager::$_data['filter']['appearence'] = isset($jslms_search_array['appearence']) ? $jslms_search_array['appearence'] : null;
                break;

            case 'jslm_category':
            case 'category':
             $catname = (is_admin()) ? 'searchname' : 'jslm_category';
             $search_userfields = JSLEARNMANAGERincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                 if($callfrom == 1){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('category')->getAdminCategorySearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_category'])){
                        $jslms_search_array['searchname'] = $search_cookie_data['searchname'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                        $jslms_search_array['pagesize'] = $search_cookie_data['pagesize'];

                    }
                }
                // Category
                jslearnmanager::$_data['filter']['searchname'] = isset($jslms_search_array['searchname']) ? $jslms_search_array['searchname'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                jslearnmanager::$_data['filter']['pagesize'] = isset($jslms_search_array['pagesize']) ? $jslms_search_array['pagesize'] : null;
                break;
                case 'jslm_country';
                   $countname = (is_admin()) ? 'countryname' : 'jslm_country';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('country')->getAdminCountrySearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_country'])){
                        $jslms_search_array['countryname'] = $search_cookie_data['countryname'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                        $jslms_search_array['pagesize'] = $search_cookie_data['pagesize'];
                    }
                }
                // Country
                jslearnmanager::$_data['filter']['countryname'] = isset($jslms_search_array['countryname']) ? $jslms_search_array['countryname'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                jslearnmanager::$_data['filter']['pagesize'] = isset($jslms_search_array['pagesize']) ? $jslms_search_array['pagesize'] : null;

                break;
               case 'users';
               case 'jslm_user';
               case 'mycourses':
                   $searchname = (is_admin()) ? 'searchname' : 'users';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('user')->getAdminUserSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_user'])){
                        $jslms_search_array['searchname'] = $search_cookie_data['searchname'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                        $jslms_search_array['email'] = $search_cookie_data['email'];
                        $jslms_search_array['mycoursetitle'] = $search_cookie_data['mycoursetitle'];
                        $jslms_search_array['mycoursecategory'] = $search_cookie_data['mycoursecategory'];
                    }
                }
                // User
                jslearnmanager::$_data['filter']['searchname'] = isset($jslms_search_array['searchname']) ? $jslms_search_array['searchname'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                jslearnmanager::$_data['filter']['email'] = isset($jslms_search_array['email']) ? $jslms_search_array['email'] : null;
                jslearnmanager::$_data['filter']['country'] = isset($jslms_search_array['country']) ? $jslms_search_array['country'] : null;
                jslearnmanager::$_data['filter']['mycoursetitle'] = isset($jslms_search_array['mycoursetitle']) ? $jslms_search_array['mycoursetitle'] : null;
                jslearnmanager::$_data['filter']['mycoursecategory'] = isset($jslms_search_array['mycoursecategory']) ? $jslms_search_array['mycoursecategory'] : null;
                break;
               case 'jslm_fieldordering';
                   $title = (is_admin()) ? 'title' : 'jslm_fieldordering';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('fieldordering')->getAdminUserFieldSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_fieldordering'])){
                        $jslms_search_array['title'] = $search_cookie_data['title'];
                        $jslms_search_array['ustatus'] = $search_cookie_data['ustatus'];
                        $jslms_search_array['vstatus'] = $search_cookie_data['vstatus'];
                        $jslms_search_array['required'] = $search_cookie_data['required'];
                        $jslms_search_array['pagesize'] = $search_cookie_data['pagesize'];
                    }
                }
                // User Field
                jslearnmanager::$_data['filter']['title'] = isset($jslms_search_array['title']) ? $jslms_search_array['title'] : null;
                jslearnmanager::$_data['filter']['ustatus'] = isset($jslms_search_array['ustatus']) ? $jslms_search_array['ustatus'] : null;
                jslearnmanager::$_data['filter']['vstatus'] = isset($jslms_search_array['vstatus']) ? $jslms_search_array['vstatus'] : null;
                jslearnmanager::$_data['filter']['pagesize'] = isset($jslms_search_array['pagesize']) ? $jslms_search_array['pagesize'] : null;
               jslearnmanager::$_data['filter']['required'] = isset($jslms_search_array['required']) ? $jslms_search_array['required'] : null;

                break;
             case 'students';
             case 'jslm_student';
             case 'studentqueue';
             case 'mycourses':
             // die();
                $studentname = (is_admin()) ? 'studentname' : 'jslm_student';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('student')->getAdminStudentSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_student'])){
                        $jslms_search_array['studentname'] = $search_cookie_data['studentname'];
                        $jslms_search_array['studentemail'] = $search_cookie_data['studentemail'];
                        $jslms_search_array['pagesize'] = $search_cookie_data['pagesize'];
                    }
                }
                // Student
                jslearnmanager::$_data['filter']['studentname'] = isset($jslms_search_array['studentname']) ? $jslms_search_array['studentname'] : null;
                jslearnmanager::$_data['filter']['studentemail'] = isset($jslms_search_array['studentemail']) ? $jslms_search_array['studentemail'] : null;
                jslearnmanager::$_data['filter']['pagesize'] = isset($jslms_search_array['pagesize']) ? $jslms_search_array['pagesize'] : null;
                break;
               case 'instructors';
               case 'jslm_instructor';
               case 'instructorqueue';
                 $instructorname = (is_admin()) ? 'instructorname' : 'jslm_instructor';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('instructor')->getAdminInstructorSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_instructor'])){
                        $jslms_search_array['instructorname'] = $search_cookie_data['instructorname'];
                        $jslms_search_array['instructoremail'] = $search_cookie_data['instructoremail'];
                    }
                }
                // instructor
                jslearnmanager::$_data['filter']['instructorname'] = isset($jslms_search_array['instructorname']) ? $jslms_search_array['instructorname'] : null;
                jslearnmanager::$_data['filter']['instructoremail'] = isset($jslms_search_array['instructoremail']) ? $jslms_search_array['instructoremail'] : null;
               
                break;

               case 'earning';
                 $instructorname = (is_admin()) ? 'instructorname' : 'jslm_instructor';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('earning')->getAdminInstructorEarningSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_instructor'])){
                        $jslms_search_array['from'] = $search_cookie_data['from'];
                        $jslms_search_array['to'] = $search_cookie_data['to'];
                        $jslms_search_array['course'] = $search_cookie_data['course'];
                    }
                }
                // instructor
                jslearnmanager::$_data['filter']['from'] = isset($jslms_search_array['from']) ? $jslms_search_array['from'] : null;
                jslearnmanager::$_data['filter']['to'] = isset($jslms_search_array['to']) ? $jslms_search_array['to'] : null;
                jslearnmanager::$_data['filter']['course'] = isset($jslms_search_array['course']) ? $jslms_search_array['course'] : null;
                break;
             case 'payout';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('payouts')->getAdminInstructorPayoutSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_instructor'])){
                         $jslms_search_array['from'] = $search_cookie_data['from'];
                        $jslms_search_array['to'] = $search_cookie_data['to'];
                        $jslms_search_array['course'] = $search_cookie_data['course'];
                    }
                }
                // instructor
                jslearnmanager::$_data['filter']['from'] = isset($jslms_search_array['from']) ? $jslms_search_array['from'] : null;
                jslearnmanager::$_data['filter']['to'] = isset($jslms_search_array['to']) ? $jslms_search_array['to'] : null;
                jslearnmanager::$_data['filter']['course'] = isset($jslms_search_array['course']) ? $jslms_search_array['course'] : null;
                break;

             case 'jslm_course';
             case 'coursequeue';
             case 'courselist';
             case 'shortlistcourses';
             case 'searchcourse';
                 $title = (is_admin()) ? 'title' : 'jslm_course';
                 if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('course')->getAdminCourseSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                    if($search_cookie_data != '' && isset($search_cookie_data['search_from_course'])){
                        $jslms_search_array['title'] = $search_cookie_data['title'];
                        $jslms_search_array['category'] = $search_cookie_data['category'];
                        $jslms_search_array['skilllevel'] = $search_cookie_data['skilllevel'];
                        $jslms_search_array['language'] = $search_cookie_data['language'];
                        $jslms_search_array['access_type'] = $search_cookie_data['access_type'];
                        $jslms_search_array['isgfcombo'] = $search_cookie_data['isgfcombo'];
                        $jslms_search_array['courselevel'] = $search_cookie_data['courselevel'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                        $jslms_search_array['isapprove'] = $search_cookie_data['isapprove'];
                        $jslms_search_array['coursetitle'] = $search_cookie_data['coursetitle'];
                        $jslms_search_array['courselevel'] = $search_cookie_data['courselevel'];
                        $jslms_search_array['sorton'] = $search_cookie_data['sorton'];
                        $jslms_search_array['sortby'] = $search_cookie_data['sortby'];
                        $jslms_search_array['instructorname'] = $search_cookie_data['instructorname'];
                    }
                }
                // Courses
                jslearnmanager::$_data['filter']['title'] = isset($jslms_search_array['title']) ? $jslms_search_array['title'] : null;
                jslearnmanager::$_data['filter']['sorton'] = isset($jslms_search_array['sorton']) ? $jslms_search_array['sorton'] : null;
                jslearnmanager::$_data['filter']['sortby'] = isset($jslms_search_array['sortby']) ? $jslms_search_array['sortby'] : null;
                jslearnmanager::$_data['filter']['category'] = isset($jslms_search_array['category']) ? $jslms_search_array['category'] : null;
                jslearnmanager::$_data['filter']['skilllevel'] = isset($jslms_search_array['skilllevel']) ? $jslms_search_array['skilllevel'] : null;
                jslearnmanager::$_data['filter']['language'] = isset($jslms_search_array['language']) ? $jslms_search_array['language'] : null;
                jslearnmanager::$_data['filter']['access_type'] = isset($jslms_search_array['access_type']) ? $jslms_search_array['access_type'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                jslearnmanager::$_data['filter']['isgfcombo'] = isset($jslms_search_array['isgfcombo']) ? $jslms_search_array['isgfcombo'] : null;
                jslearnmanager::$_data['filter']['isapprove'] = isset($jslms_search_array['isapprove']) ? $jslms_search_array['isapprove'] : null;
                jslearnmanager::$_data['filter']['coursetitle'] = isset($jslms_search_array['coursetitle']) ? $jslms_search_array['coursetitle'] : null;
                jslearnmanager::$_data['filter']['courselevel'] = isset($jslms_search_array['courselevel']) ? $jslms_search_array['courselevel'] : null;
                jslearnmanager::$_data['filter']['instructorname'] = isset($jslms_search_array['instructorname']) ? $jslms_search_array['instructorname'] : null;
              break;
              case 'jslm_courselevel';
                 $level = (is_admin()) ? 'level' : 'jslm_courselevel';
                if($callfrom == 1){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('courselevel')->getAdminCourselevelSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                   if($search_cookie_data != '' && isset($search_cookie_data['search_from_courselevel'])){
                        $jslms_search_array['level'] = $search_cookie_data['level'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                    }
                }
                // Courses
                jslearnmanager::$_data['filter']['level'] = isset($jslms_search_array['level']) ? $jslms_search_array['level'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                break;
                case 'jslm_language';
                    $language = (is_admin()) ? 'language' : 'jslm_language';
                if($callfrom == 3){
                    $jslms_search_array = JSLEARNMANAGERincluder::getJSModel('language')->getAdminLanguageSearchFormData();
                    $setcookies = true;
                 }elseif($callfrom == 2){
                    if(isset($_COOKIE['jslms_search_data'])){
                        $search_cookie_data = sanitize_key($_COOKIE['jslms_search_data']);
                        $search_cookie_data = json_decode( base64_decode($search_cookie_data) , true );
                    }
                   if($search_cookie_data != '' && isset($search_cookie_data['search_from_language'])){
                        $jslms_search_array['language'] = $search_cookie_data['language'];
                        $jslms_search_array['status'] = $search_cookie_data['status'];
                    }
                }
                // language
                jslearnmanager::$_data['filter']['language'] = isset($jslms_search_array['language']) ? $jslms_search_array['language'] : null;
                jslearnmanager::$_data['filter']['status'] = isset($jslms_search_array['status']) ? $jslms_search_array['status'] : null;
                break;
                default:
                    jslearnmanager::removeusersearchcookies();
                break;
            }
            if($setcookies){
                jslearnmanager::setusersearchcookies($setcookies,$jslms_search_array);
            }
        }

    function jslm_delete_expire_session_data(){
        global $wpdb;
        $wpdb->query('DELETE  FROM '.$wpdb->prefix.'js_learnmanager_session WHERE sessionexpire < "'.time().'"');
    }

   public static function removeusersearchcookies(){
        if(isset($_COOKIE['jslms_search_data'])){
            setcookie('jslms_search_data' , '' , time() - 3600 , COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                setcookie('jslms_search_data' , '' , time() - 3600 , SITECOOKIEPATH);
            }
        }
    }

  public static function setusersearchcookies($cookiesval, $jslms_search_array){
        if(!$cookiesval)
            return false;
        $data = json_encode( $jslms_search_array );
        $data = base64_encode($data);
        setcookie('jslms_search_data' , $data , 0 , COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            setcookie('jslms_search_data' , $data , 0 , SITECOOKIEPATH);
        }
    }
    function jslearnmanager_handle_delete_cookies(){

        if(isset($_COOKIE['jslm_addon_return_data'])){
            setcookie('jslm_addon_return_data' , '' , time() - 3600, COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                setcookie('jslm_addon_return_data' , '' , time() - 3600, SITECOOKIEPATH);
            }
        }

        if(isset($_COOKIE['jslm_addon_install_data'])){
            setcookie('jslm_addon_install_data' , '' , time() - 3600);
        }
    }
    function jslearnmanager_activate($network_wide = false) {
        include_once 'includes/activation.php';
        if(function_exists('is_multisite') && is_multisite() && $network_wide){
            global $wpdb;
            $blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogs as $blog_id){
                switch_to_blog( $blog_id );
                JSLEARNMANAGERactivation::jslearnmanager_activate();
                restore_current_blog();
            }
        }else{
            JSLEARNMANAGERactivation::jslearnmanager_activate();
        }
        wp_schedule_event(time(), 'daily', 'jslearnmanager_cronjobs_action');
        add_option('jslearnmanager_do_activation_redirect', true);
    }

    function jslearnmanager_new_site($new_site){
        $pluginname = plugin_basename(__FILE__);
        if(is_plugin_active_for_network($pluginname)){
            include_once 'includes/activation.php';
            switch_to_blog($new_site->blog_id);
            JSLEARNMANAGERactivation::jslearnmanager_activate();
            restore_current_blog();
        }
    }

    function jslearnmanager_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta){
        $pluginname = plugin_basename(__FILE__);
        if(is_plugin_active_for_network($pluginname)){
            include_once 'includes/activation.php';
            switch_to_blog($blog_id);
            JSLEARNMANAGERactivation::jslearnmanager_activate();
            restore_current_blog();
        }
    }

    function jslearnmanager_delete_site($tables){
        include_once 'includes/deactivation.php';
        $tablestodrop = JSLEARNMANAGERdeactivation::jslearnmanager_tables_to_drop();
        foreach($tablestodrop as $tablename){
            $tables[] = $tablename;
        }
        return $tables;
    }

    function jslearnmanager_deactivate($network_wide = false) {
        include_once 'includes/deactivation.php';
        if(function_exists('is_multisite') && is_multisite() && $network_wide){
            global $wpdb;
            $blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogs as $blog_id){
                switch_to_blog( $blog_id );
                JSLEARNMANAGERdeactivation::jslearnmanager_deactivate();
                restore_current_blog();
            }
        }else{
            JSLEARNMANAGERdeactivation::jslearnmanager_deactivate();
        }
    }

    /*
     * Include the required files
     */

    function includes() {
        require_once 'includes/jslearnmanagerdb.php';
        require_once 'includes/classes/class.upload.php';
        if (is_admin()) {
            include_once 'includes/jslearnmanageradmin.php';
        }
        // include_once 'includes/jslearnmanager-wc.php';
        include_once 'includes/jslearnmanager-hooks.php';
        // include_once 'includes/rss.php';
        include_once 'includes/captcha.php';
        include_once 'includes/recaptchalib.php';
        include_once 'includes/layout.php';
        include_once 'includes/pagination.php';
        include_once 'includes/includer.php';
        include_once 'includes/formfield.php';
        include_once 'includes/request.php';
        include_once 'includes/formhandler.php';
        include_once 'includes/ajax.php';
        require_once 'includes/constants.php';
        require_once 'includes/messages.php';
        include_once 'includes/shortcodes.php';
        include_once 'includes/paramregister.php';
        include_once 'includes/breadcrumbs.php';
        include_once 'includes/dashboardapi.php';
        include_once 'includes/widgets/widgets.php';
        // include_once 'includes/addon-updater/jslmupdater.php';
    }

    /*
     * Localization
     */
    public function load_plugin_textdomain() {
        // load_plugin_textdomain('learn-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        if(!load_plugin_textdomain('learn-manager')){
            load_plugin_textdomain('learn-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }else{
            load_plugin_textdomain('learn-manager');
        }

    }

    /*
     * function for the Style Sheets
     */
    static function addStyleSheets() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jslms-commonjs', JSLEARNMANAGER_PLUGIN_URL .'includes/js/common.js');
        wp_enqueue_script('jquery-ui-accordion');
        wp_localize_script('jslms-commonjs', 'common', array('ajaxurl' => admin_url('admin-ajax.php'), 'terms_conditions' => __('Please Accept Terms And Conditions So You Can Proceed','learn-manager'), 'required_fields_error_message' => __('You have not answered all required fields','learn-manager'), 'image_file_type' => JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('image_file_type'), 'allowed_file_size' => JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('allowed_file_size'),'document_file_type' => JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('file_file_type'),'file_size_exceeded' => __('File size is greater then allowed file size', 'learn-manager'),'file_extension_mismatch' => __('File ext. is mismatched', 'learn-manager')));
        wp_enqueue_script('jslms-formvalidator', JSLEARNMANAGER_PLUGIN_URL . 'includes/js/jquery.form-validator.js');
        wp_enqueue_script('jquery.table-hotkeys');
        wp_enqueue_script('jquery-ui-selectable');
        wp_enqueue_script('venobox', JSLEARNMANAGER_PLUGIN_URL .'includes/js/venobox.js');
    }

    /*
     * function to get the pageid from the wpoptions
     */
    public static function getPageid() {
        if(jslearnmanager::$_pageid != ''){
            return jslearnmanager::$_pageid;
        }else{
            $pageid = JSLEARNMANAGERrequest::getVar('page_id','GET');
            if($pageid){
                return $pageid;
            }else{
                $pageid = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid','GET');
                if($pageid){
                    return $pageid;
                }else{ // in case of categories popup
                    $module = JSLEARNMANAGERrequest::getVar('jslmsmod');
                    if($module == 'category'){
                        $pageid = JSLEARNMANAGERrequest::getVar('page_id','POST');
                        if($pageid)
                            return $pageid;
                    }
                }
            }
            $id = 0;
            $db = new jslearnmanagerdb();
            $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname = 'default_pageid'";
            $db->setQuery($query);
            $pageid = $db->loadResult();
            if ($pageid)
                $id = $pageid;
            return $id;
        }
    }

    /*
     * function to get the pageid from the wpoptions
     */
    public static function getPageidModule() {
        $id = 0;
        $db = new jslearnmanagerdb();
        $query = "SELECT configvalue FROM `#__js_learnmanager_config` WHERE configname = 'default_pageid'";
        $db->setQuery($query);
        $pageid = $db->loadResult();
        if ($pageid)
            $id = $pageid;
        return $id;
    }

    public static function setPageID($id) {
        jslearnmanager::$_pageid = $id;
    }

    /*
     * function to parse the spaces in given string
     */

    public static function parseSpaces($string) {
        return str_replace('%20', ' ', $string);
    }

    public static function tagfillin($string) {
        return str_replace(' ', '_', $string);
    }

    public static function tagfillout($string) {
        return str_replace('_', ' ', $string);
    }

    static function makeUrl($args = array()){
        global $wp_rewrite;
        $pageid = JSLEARNMANAGERrequest::getVar('jslearnmanagerpageid');
        if(is_numeric($pageid)){
            $permalink = get_the_permalink($pageid);
        }else{
            if(isset($args['jslearnmanagerpageid']) && is_numeric($args['jslearnmanagerpageid'])){
                $permalink = get_the_permalink($args['jslearnmanagerpageid']);
            }else{
                $permalink = get_the_permalink();
            }
        }
        if (!$wp_rewrite->using_permalinks()){
            if(!strstr($permalink, 'page_id') && !strstr($permalink, '?p=')){
                $page['page_id'] = get_option('page_on_front');
                $args = $page + $args;
            }
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }

        if(isset($args['jslmsmod']) && isset($args['jslmslay'])){
            // Get the original query parts
            $redirect = @parse_url($permalink);
            if (!isset($redirect['query']))
                $redirect['query'] = '';

            if(strstr($permalink, '?')){ // if variable exist
                $redirect_array = explode('?', $permalink);
                $_redirect = $redirect_array[0];
            }else{
                $_redirect = $permalink;
            }

            if($_redirect[strlen($_redirect) - 1] == '/'){
                $_redirect = substr($_redirect, 0, strlen($_redirect) - 1);
            }

            if (isset($args['jslmslay'])) {
                $layout = '';
                $layout = JSLEARNMANAGERincluder::getJSModel('slug')->getSlugFromFileName($args['jslmslay'],$args['jslmsmod']);
                global $wp_rewrite;
                $slug_prefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
                if(is_home() || is_front_page()){
                    if($_redirect == site_url()){
                        $layout = $slug_prefix.$layout;
                    }
                }else{
                    if($_redirect == site_url()){
                        $layout = $slug_prefix.$layout;
                    }
                }

                $_redirect .= '/' . $layout;
            }else{ // incase of form
                $redirect_url = add_query_arg($args,$permalink);
                return $redirect_url;
            }

            // If is jslearnmanagerid
            if (isset($args['jslearnmanagerid'])) {
                $_redirect .= '/' . $args['jslearnmanagerid'];
            }
            // If is jslearnmanagerredirecturl
            if (isset($args['jslearnmanagerredirecturl'])) {
                $_redirect .= '?jslearnmanagerredirecturl=' . $args['jslearnmanagerredirecturl'];
            }

            return $_redirect;
        }else{ // incase of form
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }
    }

    static function bjencode($array){
        return base64_encode(json_encode($array));
    }

    static function bjdecode($array){
        return base64_decode(json_encode($array));
    }

    function reset_jslm_aadon_query(){
        jslearnmanager::$_addon_query = array('select'=>'','join'=>'','where'=>'');
    }

    static function checkAddonActiveOrNot($for){
        if(in_array($for, jslearnmanager::$_active_addons)){
            return true;
        }
        return false;
    }

}


$jslearnmanager = new jslearnmanager();

function jslms_plugin_updates_ignore( $value ) {
  if( isset( $value->response['learn-manager/learn-manager.php'] ) ) {
     unset( $value->response['learn-manager/learn-manager.php'] );
   }
   return $value;
}
// add_filter( 'site_transient_update_plugins', 'jslms_plugin_updates_ignore' );

function jslearnmanager_register_plugin_styles(){
    wp_enqueue_script('jquery');
    if(!jslearnmanager::$_learn_manager_theme){
        include_once 'includes/css/site_color.php';
    }
    wp_enqueue_style('jslms-site', JSLEARNMANAGER_PLUGIN_URL .'includes/css/site.css');
    wp_enqueue_style('jslms-site-tablet', JSLEARNMANAGER_PLUGIN_URL .'includes/css/site_tablet.css',array(),'','(min-width: 661px) and (max-width: 782px)');
    wp_enqueue_style('jslms-site-mobile-landscape', JSLEARNMANAGER_PLUGIN_URL .'includes/css/site_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 660px)');
    wp_enqueue_style('jslms-site-mobile', JSLEARNMANAGER_PLUGIN_URL .'includes/css/site_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jslms-chosen-site', JSLEARNMANAGER_PLUGIN_URL .'includes/js/chosen/chosen.min.css');
    wp_enqueue_style('jslms-venobox-css', JSLEARNMANAGER_PLUGIN_URL .'includes/css/venobox.css');
    wp_enqueue_script('jquery-ui-core');
    if (is_rtl()) {
        wp_register_style('jslms-site-rtl', JSLEARNMANAGER_PLUGIN_URL .'includes/css/sitertl.css');
        wp_enqueue_style('jslms-site-rtl');
    }
}

add_action( 'wp_enqueue_scripts', 'jslearnmanager_register_plugin_styles' );

function jslearnmanager_admin_register_plugin_styles() {
    wp_enqueue_style('jslms-admin-desktop-css', JSLEARNMANAGER_PLUGIN_URL .'includes/css/admin_desktop.css',array(),'','all');
    wp_enqueue_style('jslms-admin-tablet-css', JSLEARNMANAGER_PLUGIN_URL .'includes/css/admin_tablet.css',array(),'','(min-width: 661px) and (max-width: 782px)');
    wp_enqueue_style('jslms-admin-mobile-landscape-css', JSLEARNMANAGER_PLUGIN_URL .'includes/css/admin_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 660px)');
    wp_enqueue_style('jslms-admin-mobile-css', JSLEARNMANAGER_PLUGIN_URL .'includes/css/admin_mobile.css',array(),'','(max-width: 480px)');
    if (is_rtl()) {
        wp_register_style('jslms-admincss-rtl', JSLEARNMANAGER_PLUGIN_URL .'includes/css/adminrtl.css');
        wp_enqueue_style('jslms-admincss-rtl');
    }
}
add_action( 'admin_enqueue_scripts', 'jslearnmanager_admin_register_plugin_styles' );
add_filter('style_loader_tag', 'jslmW3cValidation', 10, 2);
add_filter('script_loader_tag', 'jslmW3cValidation', 10, 2);
function jslmW3cValidation($tag, $handle) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

add_action( 'jslm_addon_update_date_failed', 'jslmaddonUpdateDateFailed' );
function jslmaddonUpdateDateFailed(){
    die();
}

if(!empty(jslearnmanager::$_active_addons)){
    require_once 'includes/addon-updater/jslmupdater.php';
    $JS_LEARNMANAGERUpdater  = new JS_LEARNMANAGERUpdater();
}


function checkLmsPluginInfo($slug){
    if(file_exists(content_url() . '/plugins/'.$slug) && is_plugin_active($slug)){
        $text = __("Activated","learn-manager");
        $disabled = "disabled";
        $class = "js-btn-activated";
        $availability = "-1";
    }else if(file_exists(content_url() . '/plugins/'.$slug) && !is_plugin_active($slug)){
        $text = __("Active Now","learn-manager");
        $disabled = "";
        $class = "js-btn-green js-btn-active-now";
        $availability = "1";
    }else if(!file_exists(content_url() . '/plugins/'.$slug)){
        $text = __("Install Now","learn-manager");
        $disabled = "";
        $class = "js-btn-install-now";
        $availability = "0";
    }
    return array("text" => $text, "disabled" => $disabled, "class" => $class, "availability" => $availability);
}
/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 * @param $upgrader_object Array
 * @param $options Array
 */
function wp_upe_upgrade_completed( $upgrader_object, $options ) {
	// The path to our plugin's main file
	$our_plugin = plugin_basename( __FILE__ );
	// If an update has taken place and the updated type is plugins and the plugins element exists
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
		// Iterate through the plugins being updated and check if ours is there
		foreach( $options['plugins'] as $plugin ) {
			if( $plugin == $our_plugin ) {
				include_once JSLEARNMANAGER_PLUGIN_PATH . 'includes/updates/updates.php';
				JSLEARNMANAGERupdates::checkUpdates();

			}
		}
	}
}
add_action( 'upgrader_process_complete', 'wp_upe_upgrade_completed', 10, 2 );

?>
