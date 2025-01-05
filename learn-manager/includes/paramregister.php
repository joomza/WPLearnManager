<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

function jslm_generate_rewrite_rules(&$rules, $rule){
    $_new_rules = array();
    foreach($rules AS $key => $value){
        if(strstr($key, $rule)){
            $newkey = substr($key,0,strlen($key) - 3);
            $matcharray = explode('$matches', $value);
            $countmatch = COUNT($matcharray);
            //on all pages
            $changename = false;
            if(file_exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-support-ticket/js-support-ticket.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/js-vehicle-manager.php')){
                $changename = true;
            }
            $controlpanel = ($changename === true) ? 'lms-controlpanel' : 'controlpanel';
            $send_message = ($changename === true) ? 'lms-send-message' : 'send-message';
            $login = ($changename === true) ? 'lms-login' : 'login';
            $register = ($changename === true) ? 'lms-register' : 'register';

            $purchase_history = ($changename === true) ? 'lms-purchase-history' : 'purchase-history';
            $myprofile = ($changename === true) ? 'lms-my-profile' : 'my-profile';
            $_key = $newkey.'/(';
            $_key .= JSLEARNMANAGERincluder::getJSModel('slug')->getSlugString();
            $_key .= ')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
            $newvalue = $value . '&jslmslayout=$matches['.$countmatch.']&jslm1=$matches['.($countmatch + 1).']&jslm2=$matches['.($countmatch + 2).']&jslm3=$matches['.($countmatch + 3).']';
            $_new_rules[$_key] = $newvalue;
        }
    }
    return $_new_rules;
}

function jslm_post_rewrite_rules_array($rules){
    $newrules = array();
    $newrules = jslm_generate_rewrite_rules($rules, '([^/]+)(?:/([0-9]+))?/?$');
    $newrules += jslm_generate_rewrite_rules($rules, '([^/]+)(/[0-9]+)?/?$');
    $newrules += jslm_generate_rewrite_rules($rules, '([0-9]+)(?:/([0-9]+))?/?$');
    $newrules += jslm_generate_rewrite_rules($rules, '([0-9]+)(/[0-9]+)?/?$');
    return $newrules + $rules;
}
add_filter('post_rewrite_rules', 'jslm_post_rewrite_rules_array');

function jslm_page_rewrite_rules_array($rules){
    $newrules = array();
    $newrules = jslm_generate_rewrite_rules($rules, '(.?.+?)(?:/([0-9]+))?/?$');
    $newrules += jslm_generate_rewrite_rules($rules, '(.?.+?)(/[0-9]+)?/?$');
    return $newrules + $rules;
}
add_filter('page_rewrite_rules', 'jslm_page_rewrite_rules_array');

function jslm_root_rewrite_rules( $rules ) {
        // // Hooks params
        // $rules = array();
        // Homepage params
        $pageid = get_option('page_on_front');
        $changename = false;
        if(file_exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
            $changename = true;
        }
        if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/js-vehicle-manager.php')){
            $changename = true;
        }
        if(file_exists(WP_PLUGIN_DIR.'/js-support-ticket/js-support-ticket.php')){
            $changename = true;
        }
        if($pageid == 0 || $pageid == ''){
            $pageid = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('default_pageid');
        }

        $key = JSLEARNMANAGERincluder::getJSModel('slug')->getSlugString(1);
        $rules['('.$key.')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$'] = 'index.php?page_id='.$pageid.'&jslmslayout=$matches[1]&jslm1=$matches[2]&jslm2=$matches[3]&jslm3=$matches[4]';
        return $rules;
}
add_filter( 'root_rewrite_rules', 'jslm_root_rewrite_rules' );

function jslm_query_var( $qvars ) {
    $qvars[] = 'jslmslayout';
    $qvars[] = 'jslm1';
    $qvars[] = 'jslm2';
    $qvars[] = 'jslm3';
    return $qvars;
}
add_filter( 'query_vars', 'jslm_query_var' , 10, 1 );

function jslm_parse_request($q) {

    if(isset($q->query_vars['jslmslayout']) && !empty($q->query_vars['jslmslayout'])){
        $layout = $q->query_vars['jslmslayout'];
        $slug_prefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
        $home_slug_prefix = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
        $length = strlen($home_slug_prefix);
        if(substr($layout, 0, $length) === $home_slug_prefix){
            $layout = substr($layout,$length);
        }
        $length = strlen($slug_prefix);
        if(substr($layout, 0, $length) === $slug_prefix){
            $slug_flag = JSLEARNMANAGERincluder::getJSModel('slug')->checkIfSlugExist($layout);
            if($slug_flag != true){
                $layout = substr($layout,$length);
            }
        }

		$layout = JSLEARNMANAGERincluder::getJSModel('slug')->getDefaultSlugFromSlug($layout);
        switch ($layout) {
            case 'add-course':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'addcourse';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'add-lecture':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'lecture';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'addlecture';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'courses-by-category':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'coursebycategory';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'course-details':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'coursedetails';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'course-list':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'courselist';
            break;
            case 'edit-course':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'editcourse';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'lecture-details':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'lecture';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'lecturedetails';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'shortlisted-courses':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'shortlistcourses';
            break;
            case 'instructor-details':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'instructor';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructordetails';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'lms-controlpanel' :
            case 'controlpanel':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'jslearnmanager';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'controlpanel';
            break;
            case 'student-messages':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'message';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'studentmessages';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'instructor-messages':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'message';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructormessages';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'student-profile':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'student';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'studentprofile';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'lms-send-message' :
            case 'send-message':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'student';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'studentsendmessage';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'instructor-dashboard':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'instructor';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructordashboard';
            break;
            case 'instructor-register':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'user';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructorregister';
            break;
            case 'lms-login' :
            case 'login':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'jslearnmanager';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'login';
            break;
            case 'lms-register' :
            case 'register':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'user';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'register';
            break;
            case 'student-dashboard':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'student';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'studentdashboard';
            break;
            case 'student-register':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'user';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'studentregister';
            break;
            case 'my-profile':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'user';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'myprofile';
            break;
            case 'user-dashboard':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'user';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'dashboard';
            break;

            case 'edit-profile':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'user';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'profileform';
            break;

            case 'message-conversation':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'message';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'messageconversation';
                if(!empty($q->query_vars['jslm1'])){
                    jslearnmanager::$_data['sanitized_args']['jslearnmanagerid'] = str_replace('/', '',$q->query_vars['jslm1']);
                }
            break;
            case 'course-search':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'coursesearch';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'coursesearch';
            break;
            case 'my-courses':
                $uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
                $utype = JSLEARNMANAGERincluder::getObjectClass('user')->getjslearnmanagerusertypeByuserid($uid);
                if($utype == 'Instructor'){
                    jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'instructor';
                }else{
                    jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'student';
                }
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'mycourses';
            break;
            case 'new-in-jslearnmanager':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'common';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'newinjslearnmanager';
            break;
            case 'instructor-payouts':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'payouts';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructorpayouts';
            break;
            case 'instructor-earning':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'earning';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructorearning';
            break;
            case 'categories-list':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'category';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'categorieslist';
            break;
            case 'instructors-list':
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'instructor';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'instructorslist';
            break;
            default:
                jslearnmanager::$_data['sanitized_args']['jslmsmod'] = 'course';
                jslearnmanager::$_data['sanitized_args']['jslmslay'] = 'courselist';
            break;
        }
    }
}
add_action('parse_request', 'jslm_parse_request');

function jslm_redirect_canonical($redirect_url, $requested_url) {
    global $wp_rewrite;
    if(is_home() || is_front_page()){
        $array = JSLEARNMANAGERincluder::getJSModel('slug')->getRedirectCanonicalArray();
        $ret = false;
        foreach($array AS $layout){
            if(strstr($requested_url, $layout)){
                $ret = true;
                break;
            }
        }
        if($ret == true){
            return $requested_url;
        }
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'jslm_redirect_canonical', 11, 2);

?>
