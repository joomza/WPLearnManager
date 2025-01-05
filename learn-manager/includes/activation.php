<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
class JSLEARNMANAGERactivation {

    static function jslearnmanager_activate() {
        // Install Database
        JSLEARNMANAGERactivation::runSQL();
        JSLEARNMANAGERactivation::insertMenu();
        JSLEARNMANAGERactivation::addCapabilites();
        JSLEARNMANAGERactivation::checkUpdates();
    }

    static private function addCapabilites() {
        $role = get_role( 'administrator' );
        $role->add_cap( 'jslearnmanager' );
        $role->add_cap( 'jslearnmanager_courses' );
        $role2 = get_role( 'contributor' );
        $role2->add_cap( 'jslearnmanager_courses' );
    }

    static private function checkUpdates() {
        include_once JSLEARNMANAGER_PLUGIN_PATH .'includes/updates/updates.php';
        JSLEARNMANAGERupdates::checkUpdates();
    }

    static private function insertMenu() {
        $db = new jslearnmanagerdb();
        $query = "SELECT COUNT(id) FROM `#__posts` WHERE post_content LIKE '%jslearnmanager page%'";
        $db->setQuery($query);
        $pageexist = $db->loadResult();
        if ($pageexist == 0) {
            $post = array(
                'post_name' => 'learn-manager-courses',
                'post_title' => 'Courses',
                'post_status' => 'publish',
                'post_content' => '[jslearnmanager page="5"]',
                'post_type' => 'page'
            );
            wp_insert_post($post);
        } else {
            $query = "UPDATE `#__posts` SET post_status = 'publish' WHERE post_content LIKE '%[jslearnmanager page=\"5\"]%'";
            $db->setQuery($query);
            $db->query();
        }
        update_option('rewrite_rules', '');
    }

    static private function runSQL() {
        $db = new jslearnmanagerdb();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_activitylog` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `description` text NOT NULL,
            `referencefor` varchar(50) NOT NULL,
            `referenceid` int(11) NOT NULL,
            `uid` int(11) NOT NULL,
            `created` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_category` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `category_name` varchar(45) DEFAULT NULL,
            `category_img` varchar(500) DEFAULT NULL,
            `alias` varchar(100) NOT NULL,
            `parentid` int(11) NOT NULL,
            `ordering` int(11) NOT NULL,
            `isdefault` int(11) NOT NULL,
            `status` int(11) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=21";
        $db->setQuery($query);
        $db->query();

        $query = " INSERT INTO `#__js_learnmanager_category` (`id`, `category_name`, `category_img`, `alias`, `parentid`, `ordering`, `isdefault`, `status`, `created_at`, `updated_at`) VALUES
            (1, 'Business', '".content_url()."/uploads/jslearnmanager_data/data/category/category_1/1.jpg', 'business', 0, 1, 1, 1, NOW(), NOW()),
            (2, 'Computer Science', '".content_url()."/uploads/jslearnmanager_data/data/category/category_2/3.jpg', 'computer-science', 0, 2, 0, 1, NOW(), NOW()),
            (3, 'English', '".content_url()."/uploads/jslearnmanager_data/data/category/category_3/8.jpg', 'english', 0, 3, 0, 1, NOW(),NOW()),
            (4, 'Consumer Science', '".content_url()."/uploads/jslearnmanager_data/data/category/category_4/11.jpg', 'consumer-science', 0, 4, 0, 1, NOW(),NOW()),
            (5, 'Foreign Language', '".content_url()."/uploads/jslearnmanager_data/data/category/category_5/5.jpg', 'foreign-language', 0, 5, 0, 1, NOW(),NOW()),
            (6, 'Engineering', '".content_url()."/uploads/jslearnmanager_data/data/category/category_6/4.jpg', 'engineering', 0, 6, 0, 1, NOW(),NOW()),
            (7, 'Maths', '".content_url()."/uploads/jslearnmanager_data/data/category/category_7/10.jpg', 'maths', 0, 7, 0, 1, NOW(),NOW()),
            (8, 'Performing Arts', '".content_url()."/uploads/jslearnmanager_data/data/category/category_8/7.jpg', 'performing-arts', 0, 8, 0, 1, NOW(),NOW()),
            (9, 'Physical Education', '".content_url()."/uploads/jslearnmanager_data/data/category/category_9/11.jpg', 'physical-education', 0, 9, 0, 1, NOW(),NOW()),
            (10, 'Science', '".content_url()."/uploads/jslearnmanager_data/data/category/category_10/12.jpg', 'science', 0, 10, 0, 1, NOW(), NOW()),
            (11, 'Social Studies', '".content_url()."/uploads/jslearnmanager_data/data/category/category_11/9.jpg', 'social-studies', 0, 11, 0, 1, NOW(), NOW()),
            (12, 'Visual Arts', '".content_url()."/uploads/jslearnmanager_data/data/category/category_12/13.jpg', 'visual-arts', 0, 12, 0, 1, NOW(),NOW()),
            (13, 'Vocational', '".content_url()."/uploads/jslearnmanager_data/data/category/category_13/5.jpg', 'vocational', 0, 13, 0, 1, NOW(),NOW())";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_config` (
            `configname` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT '',
            `configvalue` varchar(500) DEFAULT NULL,
            `configfor` varchar(200) NOT NULL,
            `addon` varchar(200) NOT NULL,
            PRIMARY KEY (`configname`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES
            ('pagination_default_page_size', '6', 'default', NULL),
            ('offline', '0', 'default', NULL),
            ('offline_message', '1', 'offline',NULL),
            ('tell_a_friend', '1', 'course', NULL),
            ('allowed_profileimage_size', '6000', 'profile' ,NULL),
            ('clientsecretgoogle', '', 'google', 'sociallogin'),
            ('tumbler_share', '0', 'socialmedia', 'socialshare'),
            ('tmenu_mycourses_instructor', '1', 'topmenu', NULL),
            ('offline_text', '<p>Offline System</p>\n', 'jslearnmanager', NULL),
            ('googleadsenseshowafter', '3', 'googleadds' , NULL),
            ('slug_prefix', 'lms-', 'default', NULL),
            ('tmenu_addcourse_instructor', '0', 'topmenu', NULL),
            ('adminemailaddress', 'admin@yourdomain.com', 'email', NULL),
            ('tmenu_message_instructor', '0', 'topmenu', 'message'),
            ('fb_share', '0', 'socialmedia', 'socialshare'),
            ('google_like', '0', 'socialmedia' , 'socialshare'),
            ('google_share', '0', 'socialmedia', 'socialshare'),
            ('blogger_share', '0', 'socialmedia' , 'socialshare'),
            ('instgram_share', '0', 'socialmedia', 'socialshare'),
            ('linkedin', '0', 'socialmedia', 'socialshare'),
            ('digg_share', '0', 'socialmedia', 'socialshare'),
            ('twitter_share', '0', 'socialmedia', 'socialshare'),
            ('pintrest_share', '0', 'socialmedia', 'socialshare'),
            ('yahoo_share', '0', 'socialmedia', 'socialshare'),
            ('date_format', 'Y-m-d', 'default', NULL),
            ('condition_per_row', '1', 'condition', NULL),
            ('defaultaddressdisplaytype', 'cc', 'default', NULL),
            ('default_pageid', '', 'default', NULL),
            ('google_map_api_key', '', 'default',NULL),
            ('data_directory', 'jslearnmanager_data', 'course',NULL),
            ('image_file_type', 'png,jpeg,gif,jpg', 'jslearnmanager',NULL),
            ('cap_on_reg_form', '1', 'captcha',NULL),
            ('apikeylinkedin', '', 'linkedin','sociallogin'),
            ('clientsecretlinkedin', '', 'linkedin','sociallogin'),
            ('apikeyxing', '', 'xing','sociallogin'),
            ('clientsecretxing', '', 'xing','sociallogin'),
            ('loginwithfacebook', '0', 'login','sociallogin'),
            ('loginwithlinkedin', '0', 'login','sociallogin'),
            ('loginwithxing', '0', 'login','sociallogin'),
            ('show_recaptcha_to_visitor', '1', 'captcha', NULL),
            ('visitor_have_shortlist_course', '1', 'course',NULL),
            ('price_thousand_separator', ',', 'default',NULL),
            ('price_numbers_after_decimel_point', '2', 'default',NULL),
            ('allow_user_to_add_course', '1', 'course',NULL),
            ('allowed_file_size', '6000', 'jslearnmanager',NULL),
            ('file_file_type', 'pdf,docx,doc,ppsx,pps,odt', 'jslearnmanager',NULL),
            ('apikeyfacebook', '', 'facebook','sociallogin'),
            ('clientsecretfacebook', '', 'facebook','sociallogin'),
            ('mailfromaddress', 'learnmanager@yourdomain.com', 'email',NULL),
            ('mailfromname', 'Learn Manager', 'email',NULL),
            ('tmenu_home_student', '1', 'topmenu',NULL),
            ('course_rss', '0', 'rss','courserss'),
            ('rss_course_title', '', 'rss','courserss'),
            ('rss_course_ttl', '0', 'rss','courserss'),
            ('rss_course_copyright', '', 'rss','courserss'),
            ('rss_course_webmaster', '', 'rss','courserss'),
            ('rss_course_editor', '', 'rss','courserss'),
            ('rss_course_image', '0', 'rss','courserss'),
            ('visitorview_js_coursesearch', '1', 'visitor',NULL),
            ('googleadsenseclient', '', 'googleadds',NULL),
            ('googleadsenseslot', '', 'googleadds',NULL),
            ('googleadsenseheight', '', 'googleadds',NULL),
            ('googleadsensewidth', '', 'googleadds',NULL),
            ('googleadsensecustomcss', '', 'googleadds',NULL),
            ('newtyped_categories', '0', 'course',NULL),
            ('featuredcourse_autoapprove', '0', 'course','featuredcourse'),
            ('activity_log_filter', '\"course\"', 'learnmanager',NULL),
            ('course_seo', '[title][category][courselevel]', 'seo',NULL),
            ('instructor_autoapprove', '1', 'user',NULL),
            ('student_autoapprove', '1', 'user',NULL),
            ('tmenu_loginlogout_instructor', '1', 'topmenu',NULL),
            ('cur_location', '1', 'jslearnmanager',NULL),
            ('register_user_redirect_page', '2', 'user',NULL),
            ('producttype', 'pro', 'default',NULL),
            ('productversion', '118', 'default',NULL),
            ('versioncode', '1.1.8', 'default',NULL),
            ('serialnumber', '81037', 'hostdata',NULL),
            ('hostdata', '1a984bbd5fb77dddcd0df7ac9a178af3', 'hostdata',NULL),
            ('zvdk', '4089a7619015f85', 'hostdata',NULL),
            ('course_auto_approve', '1', 'course',NULL),
            ('apikeygoogle', '', 'google',NULL),
            ('loginwithgoogle', '0', 'login','sociallogin'),
            ('tmenu_home_instructor', '1', 'topmenu',NULL),
            ('tmenu_courses_instructor', '1', 'topmenu',NULL),
            ('auto_assign_payment_plan', '0', 'course','paymentplan'),
            ('newdays', '5', 'course',NULL),
            ('newtyped_tags', '0', 'course',NULL),
            ('number_of_tags_for_autocomplete', '15', 'tag',NULL),
            ('system_slug', 'jslearnmanager', 'jslearnmanager',NULL),
            ('home_slug_prefix', 'js-', 'default',NULL),
            ('no_of_categories_rightbar', '6', 'course',NULL),
            ('default_curreny_forpaid', '0', 'default','paidcourse'),
            ('captcha_selection', '2', 'captcha',NULL),
            ('owncaptcha_calculationtype', '0', 'captcha',NULL),
            ('owncaptcha_subtractionans', '1', 'captcha',NULL),
            ('owncaptcha_totaloperand', '2', 'captcha',NULL),
            ('recaptcha_privatekey', '', 'captcha',NULL),
            ('recaptcha_publickey', '', 'captcha',NULL),
            ('searchcoursetag', '4', 'course',NULL),
            ('showfeaturedcourseinlistcourses', '0', 'course','featuredcourse'),
            ('nooffeaturedcourseinlisting', '0', 'course','featuredcourse'),
            ('rss_course_categories', '0', 'rss','courserss'),
            ('disable_instructor', '1', 'jslearnmanager',NULL),
            ('showinstructorlink', '1', 'jslearnmanager',NULL),
            ('system_have_featured_course', '0', 'course','featuredcourse'),
            ('register_instructor_redirect_page', '2886', 'user',NULL),
            ('award_multiple_or_maximum_rule', '0', 'awards','awards'),
            ('instructorviewstudent_js_controlpanel', '1', 'jscontrolpanel',NULL),
            ('allowed_logo_size', '3000', 'logo',NULL),
            ('allow_add_paidcourse', '0', 'course','paidcourse'),
            ('allow_add_featuredcourse', '0', 'course','featuredcourse'),
            ('max_allowed_lecturesfiles', '10', 'lecture',NULL),
            ('instructorsend_message', '0', 'message','message'),
            ('show_only_section_that_have_value', '0', 'course',NULL),
            ('instructor_course_alert_fields', '', 'course', NULL),
            ('disable_student', '1', 'jslearnmanager',NULL),
            ('rss_course_description', '', 'rss','courserss'),
            ('showstudentlink', '1', 'jslearnmanager',NULL),
            ('student_have_shortlist_course', '1', 'course',NULL),
            ('register_student_redirect_page', '2886', 'user',NULL),
            ('studentview_js_controlpanel', '1', 'jscontrolpanel',NULL),
            ('studentsend_message', '0', 'message','message'),
            ('instloginlogout', '1', 'instructorcontrolpanel',NULL),
            ('allow_enroll', '1', 'course',NULL),
            ('allow_review', '0', 'course','coursereview'),
            ('allow_view_coursedetail', '1', 'course',NULL),
            ('allow_take_quiz', '0', 'lecture','quiz'),
            ('allow_take_lecture', '1', 'lecture',NULL),
            ('student_course_alert_fields', '1', 'course',NULL),
            ('show_only_section_that_have_value_student', '1', 'course',NULL),
            ('title', 'JS LEARN MANAGER', 'default',NULL),
            ('showstudentlistincoursedetail', '1', 'course',NULL),
            ('retake_quiz', '0', 'course','retakequiz'),
            ('instructor_defaultgroup', 'subscriber', 'learnmanager',NULL),
            ('student_defaultgroup', 'subscriber', 'learnmanager',NULL),
            ('tmenu_myprofile_instructor', '0', 'topmenu',NULL),
            ('tmenu_courses_student', '1', 'topmenu',NULL),
            ('tmenu_shortlistcourse_student', '1', 'topmenu',NULL),
            ('tmenu_mycourses_student', '0', 'topmenu',NULL),
            ('tmenu_myprofile_student', '0', 'topmenu',NULL),
            ('tmenu_message_student', '0', 'topmenu','message'),
            ('tmenu_loginlogout_student', '1', 'topmenu',NULL),
            ('tmenu_home_visitor', '1', 'topmenu',NULL),
            ('tmenu_courses_visitor', '1', 'topmenu',NULL),
            ('tmenu_shortlistcourse_visitor', '1', 'topmenu',NULL),
            ('tmenu_myprofile_visitor', '1', 'topmenu',NULL),
            ('tmenu_message_visitor', '0', 'topmenu','message'),
            ('tmenu_loginlogout_visitor', '1', 'topmenu',NULL),
            ('tmenu_mycourses_visitor', '1', 'topmenu',NULL),
            ('tmenu_register_visitor', '1', 'topmenu',NULL),
            ('instructor_set_register_link', '2', 'topmenu',NULL),
            ('instructor_register_link', '', 'topmenu',NULL),
            ('student_set_register_link', '2', 'topmenu',NULL),
            ('student_register_link', '', 'topmenu',NULL);";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_country` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `country_name` varchar(75) NOT NULL,
            `country_code` varchar(3) DEFAULT NULL,
            `isenable` int(11) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=247;
        ";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_country` (`id`, `country_name`, `country_code`, `isenable`, `created_at`, `updated_at`) VALUES
            (1, 'Afghanistan', 'AF', 1, '2017-11-09 10:22:55', '2017-09-26 00:00:00'),
            (2, 'Albania', 'AL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (3, 'Algeria', 'DZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (4, 'American Samoa', 'DS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (5, 'Andorra', 'AD', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (6, 'Angola', 'AO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (7, 'Anguilla', 'AI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (8, 'Antarctica', 'AQ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (9, 'Antigua and Barbuda', 'AG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (10, 'Argentina', 'AR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (11, 'Armenia', 'AM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (12, 'Aruba', 'AW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (13, 'Australia', 'AU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (14, 'Austria', 'AT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (15, 'Azerbaijan', 'AZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (16, 'Bahamas', 'BS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (17, 'Bahrain', 'BH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (18, 'Bangladesh', 'BD', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (19, 'Barbados', 'BB', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (20, 'Belarus', 'BY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (21, 'Belgium', 'BE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (22, 'Belize', 'BZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (23, 'Benin', 'BJ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (24, 'Bermuda', 'BM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (25, 'Bhutan', 'BT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (26, 'Bolivia', 'BO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (27, 'Bosnia and Herzegovina', 'BA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (28, 'Botswana', 'BW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (29, 'Bouvet Island', 'BV', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (30, 'Brazil', 'BR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (31, 'British Indian Ocean Territory', 'IO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (32, 'Brunei Darussalam', 'BN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (33, 'Bulgaria', 'BG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (34, 'Burkina Faso', 'BF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (35, 'Burundi', 'BI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (36, 'Cambodia', 'KH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (37, 'Cameroon', 'CM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (38, 'Canada', 'CA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (39, 'Cape Verde', 'CV', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (40, 'Cayman Islands', 'KY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (41, 'Central African Republic', 'CF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (42, 'Chad', 'TD', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (43, 'Chile', 'CL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (44, 'China', 'CN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (45, 'Christmas Island', 'CX', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (46, 'Cocos (Keeling) Islands', 'CC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (47, 'Colombia', 'CO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (48, 'Comoros', 'KM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (49, 'Congo', 'CG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (50, 'Cook Islands', 'CK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (51, 'Costa Rica', 'CR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (52, 'Croatia (Hrvatska)', 'HR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (53, 'Cuba', 'CU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (54, 'Cyprus', 'CY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (55, 'Czech Republic', 'CZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (56, 'Denmark', 'DK', 1, '2018-02-01 11:49:01', '2017-09-26 00:00:00'),
            (57, 'Djibouti', 'DJ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (58, 'Dominica', 'DM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (59, 'Dominican Republic', 'DO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (60, 'East Timor', 'TP', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (61, 'Ecuador', 'EC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (62, 'Egypt', 'EG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (63, 'El Salvador', 'SV', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (64, 'Equatorial Guinea', 'GQ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (65, 'Eritrea', 'ER', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (66, 'Estonia', 'EE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (67, 'Ethiopia', 'ET', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (68, 'Falkland Islands (Malvinas)', 'FK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (69, 'Faroe Islands', 'FO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (70, 'Fiji', 'FJ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (71, 'Finland', 'FI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (72, 'France', 'FR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (73, 'France, Metropolitan', 'FX', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (74, 'French Guiana', 'GF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (75, 'French Polynesia', 'PF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (76, 'French Southern Territories', 'TF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (77, 'Gabon', 'GA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (78, 'Gambia', 'GM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (79, 'Georgia', 'GE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (80, 'Germany', 'DE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (81, 'Ghana', 'GH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (82, 'Gibraltar', 'GI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (83, 'Guernsey', 'GK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (84, 'Greece', 'GR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (85, 'Greenland', 'GL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (86, 'Grenada', 'GD', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (87, 'Guadeloupe', 'GP', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (88, 'Guam', 'GU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (89, 'Guatemala', 'GT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (90, 'Guinea', 'GN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (91, 'Guinea-Bissau', 'GW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (92, 'Guyana', 'GY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (93, 'Haiti', 'HT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (94, 'Heard and Mc Donald Islands', 'HM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (95, 'Honduras', 'HN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (96, 'Hong Kong', 'HK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (97, 'Hungary', 'HU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (98, 'Iceland', 'IS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (99, 'India', 'IN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (100, 'Isle of Man', 'IM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (101, 'Indonesia', 'ID', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (102, 'Iran (Islamic Republic of)', 'IR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (103, 'Iraq', 'IQ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (104, 'Ireland', 'IE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (105, 'Israel', 'IL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (106, 'Italy', 'IT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (107, 'Ivory Coast', 'CI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (108, 'Jersey', 'JE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (109, 'Jamaica', 'JM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (110, 'Japan', 'JP', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (111, 'Jordan', 'JO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (112, 'Kazakhstan', 'KZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (113, 'Kenya', 'KE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (114, 'Kiribati', 'KI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (115, 'Korea, Democratic People\'s Republic of', 'KP', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (116, 'Korea, Republic of', 'KR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (117, 'Kosovo', 'XK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (118, 'Kuwait', 'KW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (119, 'Kyrgyzstan', 'KG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (120, 'Lao People\'s Democratic Republic', 'LA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (121, 'Latvia', 'LV', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (122, 'Lebanon', 'LB', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (123, 'Lesotho', 'LS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (124, 'Liberia', 'LR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (125, 'Libyan Arab Jamahiriya', 'LY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (126, 'Liechtenstein', 'LI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (127, 'Lithuania', 'LT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (128, 'Luxembourg', 'LU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (129, 'Macau', 'MO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (130, 'Macedonia', 'MK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (131, 'Madagascar', 'MG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (132, 'Malawi', 'MW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (133, 'Malaysia', 'MY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (134, 'Maldives', 'MV', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (135, 'Mali', 'ML', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (136, 'Malta', 'MT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (137, 'Marshall Islands', 'MH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (138, 'Martinique', 'MQ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (139, 'Mauritania', 'MR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (140, 'Mauritius', 'MU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (141, 'Mayotte', 'TY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (142, 'Mexico', 'MX', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (143, 'Micronesia, Federated States of', 'FM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (144, 'Moldova, Republic of', 'MD', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (145, 'Monaco', 'MC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (146, 'Mongolia', 'MN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (147, 'Montenegro', 'ME', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (148, 'Montserrat', 'MS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (149, 'Morocco', 'MA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (150, 'Mozambique', 'MZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (151, 'Myanmar', 'MM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (152, 'Namibia', 'NA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (153, 'Nauru', 'NR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (154, 'Nepal', 'NP', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (155, 'Netherlands', 'NL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (156, 'Netherlands Antilles', 'AN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (157, 'New Caledonia', 'NC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (158, 'New Zealand', 'NZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (159, 'Nicaragua', 'NI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (160, 'Niger', 'NE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (161, 'Nigeria', 'NG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (162, 'Niue', 'NU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (163, 'Norfolk Island', 'NF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (164, 'Northern Mariana Islands', 'MP', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (165, 'Norway', 'NO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (166, 'Oman', 'OM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (167, 'Pakistan', 'PK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (168, 'Palau', 'PW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (169, 'Palestine', 'PS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (170, 'Panama', 'PA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (171, 'Papua New Guinea', 'PG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (172, 'Paraguay', 'PY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (173, 'Peru', 'PE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (174, 'Philippines', 'PH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (175, 'Pitcairn', 'PN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (176, 'Poland', 'PL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (177, 'Portugal', 'PT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (178, 'Puerto Rico', 'PR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (179, 'Qatar', 'QA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (180, 'Reunion', 'RE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (181, 'Romania', 'RO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (182, 'Russian Federation', 'RU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (183, 'Rwanda', 'RW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (184, 'Saint Kitts and Nevis', 'KN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (185, 'Saint Lucia', 'LC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (186, 'Saint Vincent and the Grenadines', 'VC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (187, 'Samoa', 'WS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (188, 'San Marino', 'SM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (189, 'Sao Tome and Principe', 'ST', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (190, 'Saudi Arabia', 'SA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (191, 'Senegal', 'SN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (192, 'Serbia', 'RS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (193, 'Seychelles', 'SC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (194, 'Sierra Leone', 'SL', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (195, 'Singapore', 'SG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (196, 'Slovakia', 'SK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (197, 'Slovenia', 'SI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (198, 'Solomon Islands', 'SB', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (199, 'Somalia', 'SO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (200, 'South Africa', 'ZA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (201, 'South Georgia South Sandwich Islands', 'GS', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (202, 'Spain', 'ES', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (203, 'Sri Lanka', 'LK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (204, 'St. Helena', 'SH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (205, 'St. Pierre and Miquelon', 'PM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (206, 'Sudan', 'SD', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (207, 'Suriname', 'SR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (208, 'Svalbard and Jan Mayen Islands', 'SJ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (209, 'Swaziland', 'SZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (210, 'Sweden', 'SE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (211, 'Switzerland', 'CH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (212, 'Syrian Arab Republic', 'SY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (213, 'Taiwan', 'TW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (214, 'Tajikistan', 'TJ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (215, 'Tanzania, United Republic of', 'TZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (216, 'Thailand', 'TH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (217, 'Togo', 'TG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (218, 'Tokelau', 'TK', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (219, 'Tonga', 'TO', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (220, 'Trinidad and Tobago', 'TT', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (221, 'Tunisia', 'TN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (222, 'Turkey', 'TR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (223, 'Turkmenistan', 'TM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (224, 'Turks and Caicos Islands', 'TC', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (225, 'Tuvalu', 'TV', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (226, 'Uganda', 'UG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (227, 'Ukraine', 'UA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (228, 'United Arab Emirates', 'AE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (229, 'United Kingdom', 'GB', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (230, 'United States', 'US', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (231, 'United States minor outlying islands', 'UM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (232, 'Uruguay', 'UY', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (233, 'Uzbekistan', 'UZ', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (234, 'Vanuatu', 'VU', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (235, 'Vatican City State', 'VA', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (236, 'Venezuela', 'VE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (237, 'Vietnam', 'VN', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (238, 'Virgin Islands (British)', 'VG', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (239, 'Virgin Islands (U.S.)', 'VI', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (240, 'Wallis and Futuna Islands', 'WF', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (241, 'Western Sahara', 'EH', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (242, 'Yemen', 'YE', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (243, 'Zaire', 'ZR', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (244, 'Zambia', 'ZM', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (245, 'Zimbabwe', 'ZW', 1, '2017-11-10 06:55:52', '2017-09-26 00:00:00'),
            (246, 'Ireland 2', 'PAK', 1, '2018-11-28 06:56:22', '2018-11-28 11:57:37');";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_course` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) CHARACTER SET utf8 NOT NULL,
            `alias` varchar(255) DEFAULT NULL,
            `course_code` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
            `subtitle` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
            `description` longtext CHARACTER SET utf8,
            `learningoutcomes` longtext,
            `meta_description` text,
            `logo` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
            `logofilename` varchar(255) DEFAULT NULL,
            `logoisfile` tinyint(1) DEFAULT NULL,
            `file` varchar(450) DEFAULT NULL,
            `video` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
            `price` double DEFAULT '0',
            `currency` int(11) DEFAULT NULL,
            `isdiscount` tinyint(1) DEFAULT NULL,
            `discounttype` tinyint(1) DEFAULT NULL,
            `discount_price` varchar(10) CHARACTER SET utf8 DEFAULT '0',
            `access_type` varchar(15) DEFAULT NULL,
            `category_id` int(11) DEFAULT NULL,
            `course_level` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
            `language` varchar(45) DEFAULT NULL,
            `start_date` varchar(25) DEFAULT NULL,
            `instructor_id` int(11) DEFAULT NULL,
            `course_status` int(11) DEFAULT '0',
            `keywords` text,
            `expire_date` varchar(25) DEFAULT NULL,
            `featured` int(11) DEFAULT NULL,
            `startfeatureddate` datetime NOT NULL,
            `endfeatureddate` datetime NOT NULL,
            `featuredbyuid` int(11) DEFAULT NULL,
            `course_duration` varchar(45) DEFAULT NULL,
            `isapprove` tinyint(1) DEFAULT NULL,
            `isdeleted` tinyint(4) DEFAULT NULL,
            `params` longtext,
            `paymentplan_id` int(11) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_course_access_type` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `access_type` varchar(10) CHARACTER SET utf8 NOT NULL,
            `status` int(11) DEFAULT NULL,
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_course_access_type` (`id`, `access_type`, `status`, `created_at`) VALUES
            (1, 'Free', 1, '2017-11-28 10:01:13'),
            (2, 'Paid', 0, '2017-11-28 10:01:13');";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_course_level` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `level` varchar(150) CHARACTER SET utf8 NOT NULL,
            `status` tinyint(4) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_course_level` (`id`, `level`, `status`, `created_at`, `updated_at`) VALUES
            (1, 'Intermediate', 1, '2018-10-17 06:51:54', '2019-01-07 11:22:04'),
            (2, 'Beginner', 1, '2018-10-17 06:52:04', '2019-01-07 11:22:07'),
            (3, 'Advanced', 1, '2018-10-17 06:52:10', '2019-01-07 11:22:10');";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_course_section` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(150) NOT NULL,
            `alias` varchar(150) DEFAULT NULL,
            `course_id` int(11) NOT NULL,
            `access_type` varchar(45) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `section_order` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_currencies` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(250) NOT NULL,
            `symbol` varchar(10) NOT NULL,
            `code` varchar(10) NOT NULL,
            `status` tinyint(1) NOT NULL,
            `isdefault` tinyint(1) NOT NULL,
            `ordering` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_currencies` (`id`, `title`, `symbol`, `code`, `status`, `isdefault`, `ordering`) VALUES
            (1, 'Pound', '£', 'GBP', 1, 0, 1),
            (2, 'Dollar', '$', 'USD', 1, 0, 2),
            (3, 'Euro', '€', 'EUR', 1, 0, 3),
            (4, 'Pakistan, Rupee', 'Rs', 'PKR', 1, 1, 4);";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_course_section_lecture` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `alias` varchar(255) DEFAULT NULL,
            `section_id` int(11) NOT NULL,
            `description` longtext,
            `lecture_order` int(11) DEFAULT NULL,
            `params` longtext,
            `status` tinyint(4) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_emailtemplates` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uid` int(11) DEFAULT NULL,
            `templatefor` varchar(50) DEFAULT NULL,
            `title` varchar(50) DEFAULT NULL,
            `subject` varchar(255) DEFAULT NULL,
            `body` text,
            `status` tinyint(1) DEFAULT NULL,
            `created` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=21;";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
        (1, 42, 'new-course-admin', NULL, 'JS Learn Manager: New Course', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear Admin</p>\n<p style=\"color: #848688;\">We receive new course from instructor <span class=\"jslm_js-email-paramater\">{INSTRUCTOR_NAME}</span></p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Title:</strong>{COURSE_TITLE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course Status:</strong>{COURSE_STATUS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course Category:</strong><span class=\"jslm_js-email-paramater\">{COURSE_CATEGORY}</span></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Access Type:</strong><span class=\"jslm_js-email-paramater\">{ACCESS_TYPE}</span></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Publish Status:</strong><span class=\"jslm_js-email-paramater\">{PUBLISHED_STATUS}</span></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to view :</strong><span class=\"jslm_js-email-paramater\">{COURSE_LINK}</span></td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (2, 42, 'new-course', NULL, 'JS Learn Manager: New Course', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {INSTRUCTOR_NAME}</p>\n<p style=\"color: #848688;\">We received new course</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Title:</strong><span class=\"jslm_js-email-paramater\">{COURSE_TITLE}</span></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course category:</strong>{COURSE_CATEGORY}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course status:</strong>{COURSE_STATUS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Access type:</strong>{ACCESS_TYPE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to view :</strong>{COURSE_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (18, 42, 'new-user-admin', NULL, 'JS Learn Manager: New User Registered', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear Admin</p>\n<p style=\"color: #848688;\">New user <strong>{USER_NAME} </strong>registered as a {USER_ROLE} in JS Learn Manager</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Click here to go see dashboard area: {MY_DASHBOARD_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>User Role</strong>: {USER_ROLE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (19, 42, 'featured-course-status', NULL, 'JS Learn Manager: Featured Course Status', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {INSTRUCTOR_NAME}</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Your course <strong>{COURSE_TITLE}</strong> has been {COURSE_STATUS} for featured</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Featured course status:</strong>{COURSE_STATUS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Access type:</strong>{ACCESS_TYPE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to view :</strong>{COURSE_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (4, 42, 'delete-course', NULL, 'JS Learn Manager: Course Deleted', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear <span class=\"jslm_js-email-paramater\">{INSTRUCTOR_NAME}</span></th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Your course <strong>{COURSE_TITLE}</strong> has been deleted</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (5, 42, 'course-status', NULL, 'JS Learn Manager: Course Status', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {INSTRUCTOR_NAME}</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Your course <strong>{COURSE_TITLE}</strong> has been {COURSE_STATUS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course status:</strong>{PUBLISHED_STATUS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Access type:</strong>{ACCESS_TYPE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to view :</strong>{COURSE_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (6, 42, 'credits-purchase-admin', NULL, 'JS Vehicle Manager: Credits Pack Purchased BY {SELLER_NAME}', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear Admin</p>\n<p style=\"color: #848688;\">New package {PACKAGE_NAME} purchased by {SELLER_NAME}</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Credits package name:<strong>{PACKAGE_NAME}</strong></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Package price:</strong>{PACKAGE_PRICE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Package purchase date:</strong>{PACKAGE_PURCHASE_DATE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (7, 42, 'credits-purchase', NULL, 'JS Vehicle Manager: Credits Pack Purchased', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {SELLER_NAME}</p>\n<p style=\"color: #848688;\">You have purchased new package {PACKAGE_NAME} successfully.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Credits package name:<strong>{PACKAGE_NAME}</strong></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Package price:</strong>{PACKAGE_PRICE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Package link:</strong>{PACKAGE_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Package purchase date:</strong>{PACKAGE_PURCHASE_DATE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (8, 42, 'credits-expiry', NULL, 'JS Vehicle Manager: Credits Pack Expired', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {SELLER_NAME}</p>\n<p style=\"color: #848688;\">Your {PACKAGE_NAME} has been expired.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Credits package name:<strong>{PACKAGE_NAME}</strong></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Package link:</strong>{PACKAGE_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>You have to purchased this package at:</strong>{PACKAGE_PURCHASE_DATE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (9, 42, 'new-user', NULL, 'JS Learn Manager: New User Registered', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {USER_NAME}</p>\n<p style=\"color: #848688;\">You registered in JS Learn Manager successfully.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">Click here to go your dashboard area: {MY_DASHBOARD_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Your Role</strong>: {USER_ROLE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (11, 42, 'tell-a-friend', NULL, 'JS Learn Manager: New Course', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear</p>\n<p style=\"color: #848688;\">Your Friend {SENDER_NAME} send you this mail through our site {SITE_NAME} to inform you for a course.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course title:</strong>{COURSE_TITLE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to visit:</strong>{COURSE_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Your Friend Message:</strong>{SENDER_MESSAGE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (12, 42, 'payout-email-instructor', NULL, 'JS Learn Manager: Payout to Instructor {INSTRUCTOR_NAME} ', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {INSTRUCTOR_NAME}</p>\n<p style=\"color: #848688;\">You have received {PAYOUT_AMOUNT} from admin</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Amount:</strong>{PAYOUT_AMOUNT}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Payout date:</strong>{PAYOUT_DATE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Attachment: </strong>{PAYOUT_FILE_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Link:</strong>{PAYOUT_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (13, 42, 'schedule-test-drive', NULL, 'JS Vehicle Manager: Schedule Test Drive Request From {CUSTOMER_NAME}', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {SELLER_NAME}</p>\n<p style=\"color: #848688;\">A Customer wants test drive of your vehicle {VEHICLE_TITLE}.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Customer Name:</strong>{CUSTOMER_NAME}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Customer Email:</strong>{CUSTOMER_EMAIL_ADDRESS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Phone no:</strong>{CUSTOMER_CELL_PHONE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Test Drive Day:</strong>{DAY}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Test Drive Time:</strong>{TIME}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (14, 42, 'message-to-sender', NULL, 'JS Learn Manager: Message from {SENDER_ROLE} {SENDER_NAME}', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {RECEIVER_NAME}</p>\n<p style=\"color: #848688;\">Your received a new message from {SENDER_ROLE} {SENDER_NAME}.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Sender Name:</strong>{SENDER_NAME}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Sender Email:</strong>{SENDER_EMAIL_ADDRESS}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Sender Message:</strong>{MESSAGE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (10, 42, 'course-alert', NULL, 'New Course Alert', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear subscriber</p>\n<p style=\"color: #848688;\">We received new course that match your criteria.</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>{COURSE_DATA}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (15, 42, 'courseenrollment-student', NULL, 'JS Learn Manager: Course Enrollment', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {STUDENT_NAME}</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">You have been successfully enrolled in <strong>{COURSE_TITLE}</strong></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Access Type :</strong>{ACCESS_TYPE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Price :</strong>{COURSE_PRICE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to view :</strong>{COURSE_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (16, 42, 'courseenrollment-instructor', NULL, 'JS Learn Manager: New Enrollment in course', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear {INSTRUCTOR_NAME}</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">New student enrolled in your course <strong>{COURSE_TITLE}</strong></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Student name: </strong>{STUDENT_NAME}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Student profile: </strong>{STUDENT_PROFILE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course access type: </strong>{ACCESS_TYPE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course price: </strong>{COURSE_PRICE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Click here to view :</strong>{COURSE_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (17, 42, 'courseenrollment-admin', NULL, 'JS Learn Manager: New Enrollment in course', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear Admin</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\">We received new enrollment in course <strong>{COURSE_TITLE} </strong>from student <strong>{STUDENT_NAME}</strong></td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course access type:</strong>{ACCESS_TYPE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course price:</strong>{COURSE_PRICE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Course link:</strong>{COURSE_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Student link:</strong>{STUDENT_PROFILE}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14'),
        (20, 42, 'payout-email-admin', NULL, 'JS Learn Manager: Payout to Instructor {INSTRUCTOR_NAME} ', '<table style=\"border-collapse: collapse; text-align: left; width: 100%; border: 1px solid #D2D3D5; border-top: 3px solid #0098DA;\">\n<thead style=\"background-color: #f5f5f5; border-bottom: 1px solid #D2D3D5;\">\n<tr>\n<th style=\"color: #0098da; padding: 15px 15px;\">Dear Admin</p>\n<p style=\"color: #848688;\">You have payout {PAYOUT_AMOUNT} to {INSTRUCTOR_NAME}</p>\n</th>\n</tr>\n</thead>\n<tbody>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Amount:</strong>{PAYOUT_AMOUNT}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Attachment: </strong>{PAYOUT_FILE_LINK}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Payout date:</strong>{PAYOUT_DATE}</td>\n</tr>\n<tr style=\"border-bottom: 1px solid #D2D3D5;\">\n<td style=\"padding: 15px 20px;\"><strong>Link:</strong>{PAYOUT_LINK}</td>\n</tr>\n</tbody>\n</table>\n<table style=\"border-collapse: collapse; margin-top: 10px; padding: 10px 20px; text-align: left; width: 100%; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<tbody>\n<tr>\n<td style=\"color: red; padding: 10px 20px;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></p>\n<p style=\"color: #000000; margin-top: 15px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</td>\n</tr>\n</tbody>\n</table>\n', 1, '2012-07-21 06:35:14');";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_emailtemplates_config` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `emailfor` varchar(150) NOT NULL,
            `admin` tinyint(1) NOT NULL,
            `instructor` tinyint(1) NOT NULL,
            `student` tinyint(1) NOT NULL,
            `seller_visitor` tinyint(1) NOT NULL,
            `buyer_visitor` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=12;";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_emailtemplates_config` (`id`, `emailfor`, `admin`, `instructor`, `student`, `seller_visitor`, `buyer_visitor`) VALUES
        (1, 'add_new_course', 1, 1, 0, 1, 0),
        (2, 'delete_course', 0, 1, 0, 0, 0),
        (3, 'course_status', 0, 1, 0, 1, 0),
        (9, 'payout', 1, 1, 0, 1, 0),
        (10, 'message', 0, 1, 1, 0, 0),
        (7, 'add_new_user', 1, 1, 1, 0, 0),
        (8, 'course_enrollment', 1, 1, 1, 1, 0),
        (11, 'feature_course_status', 0, 1, 0, 1, 0);";
        $db->setQuery($query);
        $db->query();

        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_fieldsordering` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `field` varchar(50) NOT NULL,
            `fieldtitle` varchar(50) NOT NULL,
            `ordering` int(11) NOT NULL,
            `fieldfor` tinyint(2) NOT NULL,
            `published` tinyint(1) NOT NULL,
            `isvisitorpublished` tinyint(1) NOT NULL,
            `sys` tinyint(1) DEFAULT NULL,
            `cannotunpublish` tinyint(1) DEFAULT NULL,
            `required` tinyint(1) DEFAULT NULL,
            `isuserfield` tinyint(1) DEFAULT NULL,
            `userfieldtype` varchar(250) DEFAULT NULL,
            `userfieldparams` text,
            `search_user` tinyint(1) DEFAULT NULL,
            `search_visitor` tinyint(1) DEFAULT NULL,
            `cannotsearch` tinyint(1) DEFAULT NULL,
            `showonlisting` tinyint(1) DEFAULT NULL,
            `cannotshowonlisting` tinyint(1) DEFAULT NULL,
            `depandant_field` varchar(250) DEFAULT NULL,
            `readonly` tinyint(4) DEFAULT NULL,
            `size` int(11) DEFAULT NULL,
            `maxlength` int(11) DEFAULT NULL,
            `cols` int(11) DEFAULT NULL,
            `rows` int(11) DEFAULT NULL,
            `j_script` text,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=34";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_fieldsordering` (`id`, `field`, `fieldtitle`, `ordering`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) VALUES
        (1, 'title', 'Course Title', 2, 1, 1, 1, 1, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, 250, NULL, NULL, ''),
        (2, 'category_id', 'Category', 5, 1, 1, 1, 0, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (3, 'logo', 'Course Logo', 1, 1, 1, 1, 0, 1, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (4, 'description', 'Description', 10, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (5, 'meta_description', 'Meta Description', 12, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, 75, 50, 5, ''),
        (6, 'keywords', 'Keywords', 13, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, 75, NULL, NULL, ''),
        (7, 'access_type', 'Access Type', 6, 1, 1, 1, 0, 1, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (8, 'price', 'Price', 8, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, ''),
        (9, 'discount_price', 'Discount price', 9, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (10, 'course_duration', 'Course Duration', 14, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, 45, NULL, NULL, ''),
        (11, 'course_code', 'Course Code', 3, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, ''),
        (12, 'course_level', 'Course Level', 15, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (13, 'currency', 'Currency', 7, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 0, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (14, 'language', 'Language', 16, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (15, 'learningoutcomes', 'Learning Outcomes', 11, 1, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (16, 'course_status', 'Course Status', 17, 1, 1, 1, 0, 1, 0, 0, '', '', 1, 1, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL, ''),
        (17, 'paymentplan_id', 'Payment Plan', 18, 1, 1, 1, 0, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (18, 'instructor_id', 'Instructor', 4, 1, 1, 1, 0, 1, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (19, 'name', 'Title', 1, 2, 1, 1, 1, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (20, 'description', 'Description', 2, 2, 1, 1, 1, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (21, 'filename', 'File Name', 3, 2, 1, 1, 0, 0, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (22, 'file_type', 'Video URL', 4, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (23, 'question', 'Question', 5, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (24, 'firstname', 'First Name', 2, 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (25, 'lastname', 'Last Name', 3, 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (26, 'email', 'Email', 4, 3, 1, 1, 0, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (27, 'weblink', 'Website', 7, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (28, 'user_image', 'Image', 1, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (29, 'gender', 'Gender', 5, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (30, 'country', 'Country', 6, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (31, 'bio', 'Biography', 8, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (32, 'sociallinks', 'Social Links', 9, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, ''),
        (33, 'approvalstatus', 'Approval Status', 10, 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, 0, 1, 1, '', NULL, NULL, NULL, NULL, NULL, '');";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_instructor` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `bio` longtext,
            `image` varchar(500) DEFAULT NULL,
            `gender` varchar(10) DEFAULT NULL,
            `user_id` int(11) NOT NULL,
            `approvalstatus` tinyint(4) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_language` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `language` varchar(50) NOT NULL,
            `status` tinyint(4) DEFAULT NULL,
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_language` (`id`, `language`, `status`, `created_at`) VALUES
        (1, 'English', 1, '2018-10-17 00:00:00'),
        (2, 'Urdu', 1, '2018-10-17 00:00:00'),
        (3, 'Chinese', 1, '2018-10-23 00:00:00'),
        (4, 'Arabic', 1, '2018-10-23 00:00:00');";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_lecture_file` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `filename` varchar(500) NOT NULL,
            `fileurl` varchar(500) DEFAULT NULL,
            `file_type` varchar(45) NOT NULL,
            `lecture_id` int(11) NOT NULL,
            `downloadable` tinyint(4) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_slug` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `slug` varchar(100) CHARACTER SET utf8 NOT NULL,
            `defaultslug` varchar(100) CHARACTER SET utf8 NOT NULL,
            `filename` varchar(100) CHARACTER SET utf8 NOT NULL,
            `description` varchar(200) CHARACTER SET utf8 NOT NULL,
            `status` tinyint(4) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=34;";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO `#__js_learnmanager_slug` (`id`, `slug`, `defaultslug`, `filename`, `description`, `status`) VALUES
        (1, 'add-course', 'add-course', 'addcourse', 'slug for add course', 1),
        (2, 'add-lecture', 'add-lecture', 'addlecture', 'slug for add lecture', 1),
        (3, 'courses-by-category', 'courses-by-category', 'coursebycategory', 'slug for courses by category', 1),
        (4, 'course-details', 'course-details', 'coursedetails', 'slug for course detail', 1),
        (5, 'course-list', 'course-list', 'courselist', 'slug for course list', 1),
        (6, 'edit-course', 'edit-course', 'editcourse', 'slug for edit course', 1),
        (7, 'lecture-details', 'lecture-details', 'lecturedetails', 'slug for lecture detail', 1),
        (8, 'shortlisted-courses', 'shortlisted-courses', 'shortlistcourses', 'slug for shortlist course', 1),
        (9, 'instructor-details', 'instructor-details', 'instructordetails', 'slug for instructor detail', 1),
        (10, 'lms-controlpanel', 'lms-controlpanel', 'controlpanel', 'slug for control panel', 1),
        (11, 'controlpanel', 'controlpanel', 'controlpanel', 'slug for control panel', 1),
        (12, 'student-messages', 'student-messages', 'studentmessages', 'slug for student message', 1),
        (13, 'instructor-messages', 'instructor-messages', 'instructormessages', 'slug for instructor message', 1),
        (14, 'student-profile', 'student-profile', 'studentprofile', 'slug for student profile', 1),
        (15, 'lms-send-message', 'lms-send-message', 'studentsendmessage', 'slug for student send message', 1),
        (16, 'send-message', 'send-message', 'studentsendmessage', 'slug for student send message', 1),
        (17, 'instructor-dashboard', 'instructor-dashboard', 'instructordashboard', 'slug for instructor dashboard', 1),
        (18, 'instructor-register', 'instructor-register', 'instructorregister', 'slug for instructor register', 1),
        (19, 'lms-login', 'lms-login', 'login', 'slug for login', 1),
        (20, 'login', 'login', 'login', 'slug for login', 1),
        (21, 'lms-register', 'lms-register', 'register', 'slug for register', 1),
        (22, 'register', 'register', 'register', 'slug for register', 1),
        (23, 'student-dashboard', 'student-dashboard', 'studentdashboard', 'slug for student dashboard', 1),
        (24, 'student-register', 'student-register', 'studentregister', 'slug for student register', 1),
        (25, 'my-profile', 'my-profile', 'myprofile', 'slug for user profile', 1),
        (26, 'user-dashboard', 'user-dashboard', 'dashboard', 'slug for user dashboard', 1),
        (27, 'edit-profile', 'edit-profile', 'profileform', 'slug for edit profile', 1),
        (28, 'message-conversation', 'message-conversation', 'messageconversation', 'slug for message history', 1),
        (29, 'course-search', 'course-search', 'coursesearch', 'slug for course search', 1),
        (30, 'my-courses', 'my-courses', 'mycourses', 'slug for my courses', 1),
        (31, 'new-in-jslearnmanager', 'new-in-jslearnmanager', 'newinjslearnmanager', 'slug for new in js learnmanager', 1),
        (32, 'instructor-earning', 'instructor-earning', 'instructorearning', 'slug for instructor earning', 1),
        (33, 'instructor-payouts', 'instructor-payouts', 'instructorpayouts', 'slug for instructor payouts', 1),
        (34, 'categories-list', 'categories-list', 'categorieslist', 'slug for Categories List', 1),
        (35, 'instructors-list', 'instructors-list', 'instructorslist', 'slug for Instructors List', 1)";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_student` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `bio` longtext,
            `user_id` int(11) NOT NULL,
            `gender` varchar(45) DEFAULT NULL,
            `image` varchar(500) DEFAULT NULL,
            `approvalstatus` tinyint(4) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_student_enrollment` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `student_id` int(11) NOT NULL,
            `course_id` int(11) NOT NULL,
            `transactionid` int(11) DEFAULT NULL,
            `quiz_result_params` longtext,
            `lecture_completion_params` longtext,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_system_errors` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uid` int(11) NOT NULL,
            `error` text NOT NULL,
            `isview` tinyint(4) NOT NULL,
            `created` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_user` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) DEFAULT NULL,
            `name` varchar(50) NOT NULL,
            `firstname` varchar(50) NOT NULL,
            `lastname` varchar(50) NOT NULL,
            `uid` int(11) NOT NULL,
            `email` varchar(50) DEFAULT NULL,
            `weblink` varchar(250) DEFAULT NULL,
            `facebook_url` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
            `twitter` varchar(250) DEFAULT NULL,
            `linkedin` varchar(250) DEFAULT NULL,
            `country_id` int(11) DEFAULT NULL,
            `user_role_id` int(11) NOT NULL,
            `status` tinyint(1) DEFAULT NULL,
            `socialid` varchar(250) DEFAULT NULL,
            `socialmedia` varchar(250) DEFAULT NULL,
            `issocial` tinyint(4) DEFAULT NULL,
            `params` longtext,
            `autogenerated` tinyint(4) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_user_role` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `role` varchar(45) NOT NULL,
            `status` int(11) NOT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;";
        $db->setQuery($query);
        $db->query();
        $query = "INSERT INTO `#__js_learnmanager_user_role` (`id`, `role`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'Instructor', 1, NULL, NULL),
        (2, 'Student', 1, NULL, NULL);";
        $db->setQuery($query);
        $db->query();


        $query = "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_wishlist` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `reviews` int(11) DEFAULT NULL,
            `student_id` int(11) NOT NULL,
            `course_id` int(11) NOT NULL,
            `created_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        $db->setQuery($query);
        $db->query();



    $query= "CREATE TABLE IF NOT EXISTS `#__js_learnmanager_session` (
          `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
          `usersessionid` char(64) NOT NULL,
          `sessionmsg` text CHARACTER SET utf8 NOT NULL,
          `sessionexpire` bigint(32) NOT NULL,
          `sessionfor` varchar(125) NOT NULL,
          `msgkey`varchar(125) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
    $db->setQuery($query);
    $db->query();



    }
}
?>
