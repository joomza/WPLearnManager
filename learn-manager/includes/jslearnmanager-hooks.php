<?php
if (!defined('ABSPATH'))
    die('Restricted Access');


// Updates login failed to send user back to the custom form with a query var
add_action( 'wp_login_failed', 'jslearnmanager_login_failed', 10, 2 );
// Updates authentication to return an error when one field or both are blank
add_filter( 'authenticate', 'jslearnmanager_authenticate_username_password', 30, 3);

function jslearnmanager_login_failed( $username ){
    $referrer = wp_get_referer();
    if ( $referrer && ! strstr($referrer, 'wp-login') && ! strstr($referrer, 'wp-admin') ){
        if (isset($_POST['wp-submit'])){
            $msgkey = JSLEARNMANAGERincluder::getJSModel('jslearnmanager')->getMessagekey();
            JSLEARNMANAGERmessages::setLayoutMessage(__('Username / password is incorrect','learn-manager'), 'error' ,$msgkey);
            wp_redirect(jslearnmanager::makeUrl(array('jslmsmod'=>'jslearnmanager', 'jslmslay'=>'login','jslearnmanagerpageid'=>jslearnmanager::getPageid())));
            exit;
        }else{
            return;
        }
    }
}

function jslearnmanager_authenticate_username_password( $user, $username, $password ){
    if ( is_a($user, 'WP_User') ) {
        return $user;
    }
    if (isset($_POST['wp-submit']) && (empty($_POST['pwd']) || empty($_POST['log']))){
        return false;
    }
    return $user;
}

// --------------------------Colorpickers for widgets--------
// load colorpicker scripts
add_action('admin_enqueue_scripts', 'jslearnmanager_my_custom_load');

function jslearnmanager_my_custom_load() {
    //wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    //our styles
    //wp_enqueue_style('jslearnmanager-widgetscss', JSLEARNMANAGER_PLUGIN_URL .'includes/css/jslearnmanager_widgets.css');
    // wp_enqueue_script('jslearnmanager-widgetsjs', JSLEARNMANAGER_PLUGIN_URL .'includes/js/jslearnmanager_widgets.js');
}

// --------Tiny mce buttons--------

// add tinyMce custom buttons
// add_action('admin_head', 'jslearnmanager_add_my_tc_button');

// function jslearnmanager_add_my_tc_button() {
//     global $typenow;
//     // check user permissions
//     if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
//         return;
//     }

//     // verify the post type
//     if (!in_array($typenow, array('post', 'page')))
//         return;
//     // check if WYSIWYG is enabled
//     if (get_user_option('rich_editing') == true) {
//         add_filter("mce_external_plugins", "jslearnmanager_add_tinymce_plugin");
//         add_filter('mce_buttons', 'jslearnmanager_register_my_tc_button');
//     }
// }

// --------------------------WP registration from fields --------
// 1. wp register form extra field
add_action('register_form', 'jslearnmanager_add_registration_fields');

function jslearnmanager_add_registration_fields() {
    //to add profile section fields to the regestration form
}

//2. Add validation. In this case, we make sure lms_role is required
add_filter('registration_errors', 'jslearnmanager_registration_errors', 10, 3);

function jslearnmanager_registration_errors($errors, $sanitized_user_login, $user_email) {

    if (isset($_POST['learnmanager_role']) && $_POST['learnmanager_role'] == 0) {

        $errors->add('user_role_error','<strong>'.__('Error','learn-manager').'</strong>:'. __('You must set jobs user role', 'learn-manager').'.');
    }

    return $errors;
}

// 3. wp register form extra field get and set to user meta
add_action('user_register', 'jslearnmanager_registration_save', 10, 1);

function jslearnmanager_registration_save($user_id) {
}

// ------------------- jslms registrationFrom request handler--------
// register a new user
function jslearnmanager_add_new_member() {
    if (isset($_POST["jslearnmanager_user_login"]) && wp_verify_nonce($_POST['jslearnmanager_learnmanager_register_nonce'], 'jslearnmanager-learnmanager-register-nonce')) {
        global $learn_manager_options;
        if (isset($learn_manager_options['show_recaptch_on_registration_form']) && $learn_manager_options['show_recaptch_on_registration_form'] == 1) {
            if(!JSLEARNMANAGERincluder::getJSModel('course')->captchaValidate()){
                return false;
            }
        }

        $formsearch = JSLEARNMANAGERrequest::getVar('JSLEARNMANAGER_newlearner_register', 'post');
        $user_role = '';
        if($formsearch == 'JSLEARNMANAGER_newlearner_register'){
            $userrole = JSLEARNMANAGERincluder::getJSModel('userrole')->getRoleIdbyName("Student");
            $role = "Student";
            $pageid = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('register_student_redirect_page');
        }elseif($formsearch == 'JSLEARNMANAGER_newinstructor_register'){
            $userrole = JSLEARNMANAGERincluder::getJSModel('userrole')->getRoleIdbyName("Instructor");
            $role = "Instructor";
            $pageid = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('register_instructor_redirect_page');
        }else{
            return false;
        }
        $gender="";
        if($_POST["gender"] == 1){
            $gender = "Male";
        }elseif($_POST["gender"] == 2){
            $gender = "Female";
        }
        $user_login = sanitize_text_field($_POST["jslearnmanager_user_login"]);
        $user_email = sanitize_email($_POST["jslearnmanager_user_email"]);
        $user_first = sanitize_text_field($_POST["jslearnmanager_user_first"]);
        $user_last = sanitize_text_field($_POST["jslearnmanager_user_last"]);
        $user_pass = sanitize_text_field($_POST["jslearnmanager_user_pass"]);
        $pass_confirm = sanitize_text_field($_POST["jslearnmanager_user_pass_confirm"]);

        // this is required for username checks
        // require_once(ABSPATH . WPINC . '/registration.php');

        if (username_exists($user_login)) {
            // Username already registered
            jslearnmanager_errors()->add('username_unavailable', __('Username already taken', 'learn-manager'));
        }
        if (!validate_username($user_login)) {
            // invalid username
            jslearnmanager_errors()->add('username_invalid', __('Invalid username', 'learn-manager'));
        }
        if ($user_login == '') {
            // empty username
            jslearnmanager_errors()->add('username_empty', __('Please enter a username', 'learn-manager'));
        }
        if (!is_email($user_email)) {
            //invalid email
            jslearnmanager_errors()->add('email_invalid', __('Invalid email', 'learn-manager'));
        }
        if (email_exists($user_email)) {
            //Email address already registered
            jslearnmanager_errors()->add('email_used', __('Email already registered', 'learn-manager'));
        }
        if ($user_pass == '') {
            // passwords do not match
            jslearnmanager_errors()->add('password_empty', __('Please enter a password', 'learn-manager'));
        }
        if ($user_pass != $pass_confirm) {
            // passwords do not match
            jslearnmanager_errors()->add('password_mismatch', __('Passwords do not match', 'learn-manager'));
        }

        if(!isset($pageid) && $pageid == ""){
            $pageid = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('register_user_redirect_page');
        }
        // $url = home_url();
        // if(is_page($pageid)){
        //     if(get_post_status($pageid) == 'publish'){
        //         $url = get_the_permalink($pageid);
        //     }
        // }



        $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('captcha');
        if ($config_array['cap_on_reg_form'] == 1) {
            if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                $gresponse = filter_var($_POST['g-recaptcha-response'], FILTER_SANITIZE_STRING);

                $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                if (! $resp) {
                    jslearnmanager_errors()->add('invalid_captcha', __('Invalid captcha', 'learn-manager'));
                }
            } else { // own captcha
                $captcha = new JSLEARNMANAGERcaptcha;
                $result = $captcha->checkCaptchaUserForm();
                if ($result != 1) {
                    jslearnmanager_errors()->add('invalid_captcha', __('Invalid captcha', 'learn-manager'));
                }
            }
        }
        $errors = jslearnmanager_errors()->get_error_messages();
        // only create the user in if there are no errors
        if (empty($errors)) {
            $new_user_id = wp_insert_user(array(
                'user_login' => $user_login,
                'user_pass' => $user_pass,
                'user_email' => $user_email,
                'first_name' => $user_first,
                'last_name' => $user_last,
                'user_registered' => date_i18n('Y-m-d H:i:s'),
                'role' => 'subscriber'
                )
            );
            if ($new_user_id) {
                // send an email to the admin alerting them of the registration
                wp_new_user_notification($new_user_id);
                // log the new user in
                wp_set_current_user($new_user_id, $user_login);
                wp_set_auth_cookie($new_user_id);
                // do_action('wp_login', $user_login, $user);


                // insert entry into out db also

                $url = '';
                $data = JSLEARNMANAGERrequest::get('post');
                $data['uid'] = $new_user_id;
                $data['name'] = $user_first.' '.$user_last;
                $data['email'] = $user_email;
                $data['username'] = $user_login;
                $data['created_at'] = date_i18n('Y-m-d H:i:s');
                $data['status'] = 1;
                $data['id'] = '';
                if(is_numeric($data['gender'])){
                    $data['gender'] = $gender;
                }
                $data['user_role_id'] = $userrole;
                $data['role'] = $role;
                $data['firstname'] = $user_first;
                $data['lastname'] = $user_last;
                $user = JSLEARNMANAGERincluder::getJSModel('user')->storeProfile($data);
                $key = JSLEARNMANAGERincluder::getJSModel('user')->getMessagekey();
                if($user == JSLEARNMANAGER_SAVED){
                    JSLEARNMANAGERmessages::setLayoutMessage(__('User Added Successfully', 'learn-manager'), 'updated',$key);
                }else{
                    JSLEARNMANAGERmessages::setLayoutMessage(__('Error Updating User', 'learn-manager'), 'error',$key);
                }

                if(is_numeric($pageid)){
                    if(get_post_status($pageid) == 'publish'){
                        if($data['role'] == 'Instructor'){
                            if(jslearnmanager::$_config['instructor_set_register_link'] == 2){
                                $url =jslearnmanager::$_config['instructor_register_link'];
                            }else{
                                $url = get_the_permalink($pageid);
                            }
                        }elseif($data['role'] == 'Student'){
                            if(jslearnmanager::$_config['student_set_register_link'] == 2){
                                $url =jslearnmanager::$_config['student_register_link'];
                            }else{
                                $url = get_the_permalink($pageid);
                            }
                        }
                    }
                }else{
                    $url = jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'courselist'));
                }
                wp_redirect($url);
                exit;
            }
        }
    }
}
add_action('init', 'jslearnmanager_add_new_member');

// used for tracking error messages
function jslearnmanager_errors() {
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function jslearnmanager_show_error_messages() {
    if ($codes = jslearnmanager_errors()->get_error_codes()) {
        echo '<div class="jslearnmanager_errors">';
        // Loop error codes and display errors
        foreach ($codes as $code) {
            $message = jslearnmanager_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . esc_html( $message ) . '</span><br/>';
        }
        echo '</div>';
    }
}

// ---------------Remove wp user ---------------

function jslearnmanager_remove_lms_user($user_id) {
    $js_model = JSLEARNMANAGERincluder::getJSModel('user');
    $userid = $js_model->getUserIDByWPUid($user_id);

    if (isset($_POST['delete_option']) AND $_POST['delete_option'] == 'delete') {
        $result = $js_model->deleteUserRecords($userid , 0 );
        if ($result) {

        } else {

        }
    }
}
add_action('delete_user', 'jslearnmanager_remove_lms_user');
?>
