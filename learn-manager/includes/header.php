<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

$layout = JSLEARNMANAGERrequest::getVar('jslmslay');
$module = JSLEARNMANAGERrequest::getVar('jslmsmod');
$uid = JSLEARNMANAGERincluder::getObjectClass('user')->uid();
$isstudent = JSLEARNMANAGERincluder::getObjectClass('user')->isStudent($uid);
$isInstructor = JSLEARNMANAGERincluder::getObjectClass('user')->isInstructor($uid);
$isguest = JSLEARNMANAGERincluder::getObjectClass('user')->isguest();


$div = '';
$homeclass = (($module == 'course' || $module == 'lecture') && ($layout == 'courselist' || $layout == 'coursedetails' || $layout == 'lecturedetails') ) ? 'active' : '';
$dashboardclass = ($layout == 'instructordashboard' || $layout == 'studentdashboard' || $layout == 'dashboard' || $layout == 'instructorpayouts' || $layout == 'instructorearning') ? 'active' : '';
$profileclass = (($module == 'user' && $layout == 'myprofile') || $layout == 'instructordetails' || $layout == 'studentprofile' || $layout == 'profileform') ? 'active' : '';
$userregister = (($module == 'user' && $layout == 'register') || ($module == 'user' && $layout == 'instructorregister') || ($module == 'user' && $layout == 'studentregister')) ? 'active' : '';
$loginclass = ($module == 'jslearnmanager' && $layout == 'login') ? 'active' : '';
$shortlistcourse = ($module == 'course' && $layout == 'shortlistcourses') ? 'active' : '';
$home = ($module == '' && $layout == '') ? 'active' : '';
$messageclass = ($module == 'message') ? 'active' : '';
$mycoursesclass = ($layout == 'mycourses' || $layout == 'editcourse' || $layout == 'addcourse' || $layout == 'addlecture') ? 'active' : '';
$searchcourse = ($layout == 'coursesearch' && $module == 'coursesearch') ? 'active' : '';
$config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('topmenu');
$showvalue = false;
if($isguest && $config_array['tmenu_courses_visitor']){
    $showvalue = true;
}elseif($isInstructor && $config_array['tmenu_courses_instructor']){
    $showvalue = true;
}elseif($isstudent && $config_array['tmenu_courses_student']){
    $showvalue = true;
}else{
    $showvalue = false;
}
if($showvalue || !JSLEARNMANAGERincluder::getObjectClass('user')->isJSLearnManagerUser()){
    $linkarray[] = array(
        'class' => $homeclass ." ". $home,
        'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'courselist')),
        'title' => __('Courses', 'learn-manager'),
    );
}

$linkarray[] = array(
    'class' => $searchcourse,
    'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'coursesearch', 'jslmslay'=>'coursesearch')),
    'title' => __('Course Search','learn-manager'),
);

if ($isguest || $isstudent) {
    $showvalue = false;
    if($isguest && $config_array['tmenu_shortlistcourse_visitor'] == 1){
        $showvalue = true;
    }elseif($isstudent && $config_array['tmenu_shortlistcourse_student'] == 1){
        $showvalue = true;
    }
    if ($showvalue) {
        $linkarray[] = array(
            'class' => $shortlistcourse,
            'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'shortlistcourses')),
            'title' => __('Shortlist Courses', 'learn-manager'),
        );
    }
    $showvalue = false;
}

if($isguest && $config_array['tmenu_myprofile_visitor'] == 1){
    $showvalue = true;
}elseif($isInstructor && $config_array['tmenu_myprofile_instructor'] == 1){
    $showvalue = true;
}elseif($isstudent && $config_array['tmenu_myprofile_student'] == 1){
    $showvalue = true;
}else{
    $showvalue = false;
}
if($showvalue){
    $linkarray[] = array(
        'class' => $profileclass,
        'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'myprofile')),
        'title' => __('My Profile', 'learn-manager'),
    );
}
if($isguest && $config_array['tmenu_register_visitor'] == 1){
    $linkarray[] = array(
        'class' => $userregister,
        'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'register')),
        'title' => __('Register', 'learn-manager'),
    );
}
if(!$isguest){
    if($isInstructor && $config_array['tmenu_mycourses_instructor'] == 1){
        $linkarray[] = array(
            'class' => $mycoursesclass,
            'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'mycourses')),
            'title' => __('My Courses', 'learn-manager'),
        );
    }elseif($isstudent && $config_array['tmenu_mycourses_student'] == 1){
        $linkarray[] = array(
            'class' => $mycoursesclass,
            'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'mycourses')),
            'title' => __('My Courses', 'learn-manager'),
        );
    }
    if($isInstructor && $config_array['tmenu_message_instructor'] == 1){
        $linkarray[] = array(
            'class' => $messageclass,
            'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'message', 'jslmslay'=>'instructormessages')),
            'title' => __('Message', 'learn-manager'),
        );
    }elseif($isstudent && $config_array['tmenu_message_student'] == 1){
        $linkarray[] = array(
            'class' => $messageclass,
            'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'message', 'jslmslay'=>'studentmessages')),
            'title' => __('Message', 'learn-manager'),
        );
    }
}

if($isguest && $config_array['tmenu_loginlogout_visitor'] == 1){
    $linkarray[] = array(
        'class' => $loginclass,
        'link' => jslearnmanager::makeUrl(array('jslmsmod'=>'jslearnmanager', 'jslmslay'=>'login')),
        'title' => __('Login', 'learn-manager'),
    );
}else{
    if($isstudent && $config_array['tmenu_loginlogout_student'] == 1){
        $showvalue = true;
    }elseif($isInstructor && $config_array['tmenu_loginlogout_instructor'] == 1){
        $showvalue = true;
    }else{
        $showvalue = false;
    }
    if($showvalue){
        $logout_url = wp_logout_url( home_url() );
        if (isset($_SESSION['jslearnmanager-socialmedia']) && !empty($_SESSION['jslearnmanager-socialmedia'])) {
            switch (sanitize_key($_SESSION['jslearnmanager-socialmedia'])) {
                case 'facebook':
                        $logout_url =  jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'task'=>'logout', 'action'=>'jslmstask', 'media'=>'facebook', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()));
                    break;
                case 'linkedin':
                        $logout_url =  jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'task'=>'logout', 'action'=>'jslmstask', 'media'=>'linkedin', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()));
                    break;
                case 'xing':
                        $logout_url =  jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'task'=>'logout', 'action'=>'jslmstask', 'media'=>'xing', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()));
                    break;
                case 'google':
                        $logout_url =  jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'task'=>'logout', 'action'=>'jslmstask', 'media'=>'google', 'jslearnmanagerpageid'=>jslearnmanager::getPageid()));
                    break;
                default:
                    $logout_url = wp_logout_url( home_url() );
                    break;
            }
        }

        $linkarray[] = array(
            'class' => '',
            'link' =>   $logout_url,
            'title' => __('Logout', 'learn-manager'),
        );

    }
}
if (isset($linkarray)) {
    $div .= '<div id="jslearnmanager-header-main-wrapper">
            <nav><ul>';
                if($isguest && $config_array['tmenu_home_visitor'] == 1){
                    $showvalue = true;
                }elseif($isInstructor && $config_array['tmenu_home_instructor'] == 1){
                    $showvalue = true;
                }elseif($isstudent && $config_array['tmenu_home_student'] == 1){
                    $showvalue = true;
                }
                $div .= '<li><a class="headerlinks '.$dashboardclass.'" href='.jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'dashboard')).'><i class="fa fa-lg fa-home"></i></a></li>';
                foreach ($linkarray AS $link) {
                    $div .= '<li><a class="headerlinks '. $link['class'] .'" href="' . $link['link'] . '">' . $link['title'] . '</a></li>';
                }
                $div .= '</ul></nav></div>';
}

echo wp_kses($div,JSLEARNMANAGER_ALLOWED_TAGS);
?>
