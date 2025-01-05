<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
class JSLEARNMANAGERshortcodes {
	 function __construct() {
        add_shortcode('jslearnmanager', array($this, 'show_control_panel'));
        add_shortcode('jslearnmanager', array($this, 'learn_manager_pages'));
        add_shortcode('jslearnmanager_course_search', array($this, 'show_course_search'));
    }

    function learn_manager_pages($raw_args, $content = null){
        ob_start();
        $defaults = array(
            'page' => "",
            'tell_a_friend' => "",
            'title' => __('Thank you','learn-manager'),
            'message' => __('Please add your text field for the ','learn-manager'),
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jslearnmanager::$_data['sanitized_args']) && !empty(jslearnmanager::$_data['sanitized_args'])){
            jslearnmanager::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jslearnmanager::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = JSLEARNMANAGERrequest::getVar('page_id');
        if(!$pageid)  $pageid = get_the_ID();
        jslearnmanager::setPageID($pageid);
        jslearnmanager::addStyleSheets();
        $offline = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSLEARNMANAGERlayout::getSystemOffline();
        }elseif(JSLEARNMANAGERincluder::getObjectClass('user')->isdisabled()){
            JSLEARNMANAGERlayout::getUserDisabledMsg();
        }else {
            switch($sanitized_args['page']){
                case 1: // Add Course
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'addcourse' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 2: // Add Lecture
                    $module = 'lecture';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'addlecture' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 3: // Course By Category
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'coursebycategory' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 4: // Course Details
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'coursedetails' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 5: // Course List
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'courselist' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 6: // Edit Course
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'editcourse' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 7: // Lecture Details
                    $module = 'lecture';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'lecturedetails' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 8: // Shortlist Courses
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'shortlistcourses' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;

                case 9: // Instructor Details
                    $module = 'instructor';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructordetails' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 10: // Controlpanel
                    $module = 'jslearnmanager';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'controlpanel' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 11: // Student Message
                    $module = 'message';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'studentmessages' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 12: // Student Profile
                    $module = 'student';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'studentprofile' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 13: // Student Send Message
                    $module = 'message';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'studentmessages' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 14: // Instructor Dashboard
                    $module = 'instructor';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructordashboard' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 15: // Instructor Register
                    $module = 'user';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructorregister' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 16: // Login
                    $module = 'jslearnmanager';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'login' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 17: // Register
                    $module = 'user';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'register' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 18: // Student Dashboard
                    $module = 'student';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'studentdashboard' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 19: // Student Register
                    $module = 'user';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'studentregister' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 20: // Thank You
                    $module = 'jslearnmanager';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'thankyou' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 21: // Dashboard
                    $module = 'user';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'dashboard' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 22: // My Profile
                    $module = 'user';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'myprofile' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 23: // Edit Profile
                    $module = 'user';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'profileform' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;

                case 24: // Instructor Messages
                    $module = 'message';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructormessages' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;

                case 25: // Instructor My courses
                    $module = 'instructor';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'mycourses' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 26: // Student My courses
                    $module = 'student';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'mycourses' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 27: // New in js learnmanager
                    $module = 'common';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'newinjslearnmanager' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 28: // Message Conversation
                    $module = 'message';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'messageconversation' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 29: // Instructor Earning
                    $module = 'earning';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructorearning' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 30: // Instructor Payouts
                    $module = 'payouts';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructorpayouts' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 31: // All Instructor
                    $module = 'instructor';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'instructorslist' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 32: // Categories List
                    $module = 'category';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'categorieslist' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                case 33: // Categories List
                    $module = 'coursesearch';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'coursesearch' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
                default:
                    $module = 'course';
                    jslearnmanager::$_data['sanitized_args']['jslmslay'] = (!isset(jslearnmanager::$_data['sanitized_args']['jslmslay']) || empty(jslearnmanager::$_data['sanitized_args']['jslmslay'])) ? 'courselist' : jslearnmanager::$_data['sanitized_args']['jslmslay'];
                break;
            }
            $c_mod = JSLEARNMANAGERrequest::getVar('jslmsmod');
            if($c_mod){
                $module = $c_mod;
            }
            JSLEARNMANAGERincluder::include_file($module);
        }
        $content .= ob_get_clean();
        return $content;
    }

    function show_control_panel($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jslmsmod' => 'user',
            'jslmslay' => 'dashboard',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jslearnmanager::$_data['sanitized_args']) && !empty(jslearnmanager::$_data['sanitized_args'])){
            jslearnmanager::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jslearnmanager::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jslearnmanager::setPageID($pageid);
        jslearnmanager::addStyleSheets();
        $offline =JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSLEARNMANAGERlayout::getSystemOffline();
        } elseif (JSLEARNMANAGERincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSLEARNMANAGERlayout::getUserDisabledMsg();
        } else {
            $module = JSLEARNMANAGERrequest::getVar('jslmsmod', null, 'user');
            $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'dashboard');
            JSLEARNMANAGERincluder::include_file($module);
        }
        $content .= ob_get_clean();
        return $content;
    }

    function show_course_search($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'jslmsmod' => 'coursesearch',
            'jslmslay' => 'coursesearch',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(jslearnmanager::$_data['sanitized_args']) && !empty(jslearnmanager::$_data['sanitized_args'])){
            jslearnmanager::$_data['sanitized_args'] += $sanitized_args;
        }else{
            jslearnmanager::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        jslearnmanager::setPageID($pageid);
        jslearnmanager::addStyleSheets();
        $offline =JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            JSLEARNMANAGERlayout::getSystemOffline();
        } elseif (JSLEARNMANAGERincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            JSLEARNMANAGERlayout::getUserDisabledMsg();
        } else {
            $module = JSLEARNMANAGERrequest::getVar('jslmsmod', null, 'coursesearch');
            $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'coursesearch');
            JSLEARNMANAGERincluder::include_file($module);
        }
        $content .= ob_get_clean();
        return $content;
    }

}
$shortcodes = new JSLEARNMANAGERshortcodes();
?>
