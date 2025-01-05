<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERbreadcrumbs{

    static function getBreadcrumbs() {
        $cur_location = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('cur_location');
        if ($cur_location != 1){
            return false;
        }
        if (!is_admin()) {
            $editid = JSLEARNMANAGERrequest::getVar('jslearnmanagerid');
            $isnew = ($editid == null) ? true : false;
            $module = JSLEARNMANAGERrequest::getVar('jslmsmod');
            $layout = JSLEARNMANAGERrequest::getVar('jslmslay');
            $array[] = array('link' => get_the_permalink(), 'text' => __('Control Panel', 'learn-manager'));
            if ($module != null) {
                switch ($module) {
                    case 'user':
                        // Add default module link
                        switch ($layout) {
                            case 'dashboard':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'dashboard')), 'text' => __('Dashboard', 'learn-manager'));
                            break;
                            case 'register':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'register')), 'text' => __('Register', 'learn-manager'));
                            break;
                            case 'myprofile':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'myprofile')), 'text' => __('My Profile', 'learn-manager')); 
                            break;
                            case 'studentregister':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'studentregister')), 'text' => __('Student Register', 'learn-manager'));
                            break;
                            case 'instructorregister':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'instructorregister')), 'text' => __('Instructor Register', 'learn-manager'));
                            break;
                            case 'profileform':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'profileform')), 'text' => __('Edit Profile', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'course':
                        // Add default module link
                        switch ($layout) {
                            case 'addcourse':
                                $text = ($isnew) ? __('Add','learn-manager') .' '. __('Course', 'learn-manager') : __('Edit','learn-manager') .' '. __('Course', 'learn-manager');
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'addcourse')), 'text' => $text);
                            break;
                            case 'courselist':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'courselist')), 'text' => __('Courses', 'learn-manager'));
                            break;
                            case 'coursedetails':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'coursedetails')), 'text' => __('Course Detail', 'learn-manager'));
                            break;
                            case 'editcourse':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'editcourse')), 'text' => __('Edit Course', 'learn-manager'));
                            break;
                            case 'shortlistcourses':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'course', 'jslmslay'=>'shortlistcourses')), 'text' => __('Shortlist Courses', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'coursesearch':
                        switch($layout){
                            case 'coursesearch':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'coursesearch', 'jslmslay'=>'coursesearch')), 'text' => __('Search Courses', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'lecture':
                        switch($layout){
                            case 'lecturedetails':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'lecturedetails')), 'text' => __('Lecture Detail', 'learn-manager'));
                            break;
                            case 'addlecture':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'lecture', 'jslmslay'=>'addlecture')), 'text' => __('Add Lecture', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'student':
                        switch($layout){
                            case 'studentprofile':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'studentprofile')), 'text' => __('Student Profile', 'learn-manager'));
                            break;
                            case 'mycourses':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'mycourses')), 'text' => __('My Courses', 'learn-manager'));
                            break;
                            case 'studentdashboard':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'student', 'jslmslay'=>'studentdashboard')), 'text' => __('Student Dashboard', 'learn-manager'));
                            break;    
                        }
                    break;
                    case 'instructor':
                        switch($layout){
                            case 'instructordetails':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'instructordetails')), 'text' => __('Instructor Profile', 'learn-manager'));
                            break;
                            case 'mycourses':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'mycourses')), 'text' => __('My Courses', 'learn-manager'));
                            break;
                            case 'instructordashboard':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'instructordashboard')), 'text' => __('Instructor Dashboard', 'learn-manager'));
                            break;
                            case 'instructorslist':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'instructor', 'jslmslay'=>'instructorslist')), 'text' => __('All Instructors', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'message':
                        switch($layout){
                            case 'studentmessages':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'message', 'jslmslay'=>'studentmessages')), 'text' => __('Student Message', 'learn-manager'));
                            break;
                            case 'instructormessages':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'message', 'jslmslay'=>'instructormessages')), 'text' => __('Instructor Message', 'learn-manager'));
                            break;
                            case 'messageconversation':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'message', 'jslmslay'=>'messageconversation')), 'text' => __('Message Conversation', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'jslearnmanager':
                        // Add default module link
                        switch ($layout) {
                            case 'login':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'jslearnmanager', 'jslmslay'=>'login')), 'text' => __('Login', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'category':
                        // Add default module link
                        switch ($layout) {
                            case 'categorieslist':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'category', 'jslmslay'=>'categorieslist')), 'text' => __('Categories List', 'learn-manager'));
                            break;
                        }
                    break;
                    case 'common':
                        // Add default module link
                        switch ($layout) {
                            case 'newinjslearnmanager':
                                $array[] = array('link' => jslearnmanager::makeUrl(array('jslmsmod'=>'common', 'jslmslay'=>'newinjslearnmanager')), 'text' => __('New Register', 'learn-manager'));
                            break;
                        }
                    break;    
                }
            }
        }
        if (isset($array)) {
            $count = count($array);
            $i = 0;
            echo '<div id="jslms_breadcrumbs_parent">';
            foreach ($array AS $obj) {
                if ($i == 0) {
                    echo '<div class="home"><a href="' . esc_url( $obj['link'] ) . '"><img class="homeicon" src="'.JSLEARNMANAGER_PLUGIN_URL . 'includes/images/homeicon.png"/></a></div>';
                } else {
                    if ($i == ($count - 1)) {
                        echo '<div class="lastlink">' . esc_html( $obj['text'] ) . '</div>';
                    } else {
                        echo '<div class="links"><a class="links" href="' . esc_url( $obj['link'] ) . '">' . esc_html( $obj['text'] ) . '</a></div>';
                    }
                }
                $i++;
            }
            echo '</div>';
        }
    }
}

$JSLEARNMANAGERbreadcrumbs = new JSLEARNMANAGERbreadcrumbs;
?>
