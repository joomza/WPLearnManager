<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERdeactivation {

    static function jslearnmanager_deactivate() {
        wp_clear_scheduled_hook('jslearnmanager_cronjobs_action');
        $id = jslearnmanager::getPageid();
        $db = new jslearnmanagerdb();
        $query = "UPDATE `#__posts` SET post_status = 'draft' WHERE ID = " . $id;
        $db->setQuery($query);
        $db->query();

        //Delete capabilities
        $role = get_role( 'administrator' );
        $role->remove_cap( '' );
    }

    static function jslearnmanager_tables_to_drop() {
        global $wpdb;
        $tables = array(
           $wpdb->prefix."js_learnmanager_activitylog",
           $wpdb->prefix."js_learnmanager_category",
           $wpdb->prefix."js_learnmanager_config",
           $wpdb->prefix."js_learnmanager_country",
           $wpdb->prefix."js_learnmanager_course",
           $wpdb->prefix."js_learnmanager_course_access_type",
           $wpdb->prefix."js_learnmanager_course_level",
           $wpdb->prefix."js_learnmanager_course_section",
           $wpdb->prefix."js_learnmanager_currencies",
           $wpdb->prefix."js_learnmanager_course_section_lecture",
           $wpdb->prefix."js_learnmanager_emailtemplates",
           $wpdb->prefix."js_learnmanager_emailtemplates_config",
           $wpdb->prefix."js_learnmanager_fieldsordering",
           $wpdb->prefix."js_learnmanager_instructor",
           $wpdb->prefix."js_learnmanager_lecture_file",
           $wpdb->prefix."js_learnmanager_language",
           $wpdb->prefix."js_learnmanager_slug",
           $wpdb->prefix."js_learnmanager_student",
           $wpdb->prefix."js_learnmanager_student_enrollment",
           $wpdb->prefix."js_learnmanager_system_errors",
           $wpdb->prefix."js_learnmanager_user",
           $wpdb->prefix."js_learnmanager_user_role",
           $wpdb->prefix."js_learnmanager_wishlist",
           $wpdb->prefix."js_learnmanager_session",
        );
        return $tables;
    }

}

?>
