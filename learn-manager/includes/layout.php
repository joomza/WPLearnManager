<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERlayout {

    static function getNoRecordFound($message = null, $linkarray = array() , $fromadmin = 0) {        
        if($message == null){
            $message = __('No Data Found ', 'learn-manager');
        }
        $html = '<div class="jslm_content_wrapper ">
                    <div class="jslm_content_data jslm_full_width">
                        <div class="jslm_error_message_wrapper jslm_no_border">
                            <div class="jslm_error_image">
                                <div class="jslm_error_image_wrapper">
                                    <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/no-record-icon.png">
                                </div>
                            </div>
                            <div class="jslm_error_message">
                                <h2 class="jslm_no_record_found jslm_no_padding jslm_font_weight">'.__('Sorry','learn-manager').'!</h2>
                                <h2 class="jslm_no_record_found jslm_no_padding jslm_font_weight">'.$message.'....!</h2>
                            </div>
                        </div>
                    </div>
                </div>';
        if($fromadmin == 1){
            return $html;
        }else{
            echo wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);    
        }
    }

    static function getPageNotFound() {        
        $html = '<div class="jslm_content_wrapper ">
                    <div class="jslm_content_data">
                        <div class="jslm_error_message_wrapper">
                            <div class="jslm_error_image">
                                <div class="jslm_error_image_wrapper">
                                    <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/404-icon.png">
                                </div>
                            </div>
                            <div class="jslm_error_message">
                                <h1 class="jslm_heading_style jslm_font_weight">'.__('Sorry','learn-manager').'!'.__('Page has not been found','learn-manager').'</h1>
                                <div class="jslm_error_message">
                                    <h4 class="jslm_heading_style jslm_no_padding">
                                        '.__('The page link might be corrupted or','learn-manager').'
                                    </h4>
                                    <h4 class="jslm_heading_style jslm_no_padding">
                                       '.__('might be removed!','learn-manager').'
                                    </h4>
                                </div>
                            </div>
                            <div class="jslm_back_button">
                                <h6 class="jslm_back_button_heading"> <a href="#" class="jslm_back_button_style">'.__('Back to home','learn-manager').'</a></h6>
                            </div>
                        </div>
                    </div>
                </div>';
        echo wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);
    }

    static function getRegistrationNotAllow($return=0){
        $html='
            <div class="jslm_content_wrapper ">
                <div class="jslm_content_data">
                    <div class="jslm_error_message_wrapper jslm_no_border">
                        <div class="jslm_error_image">
                            <div class="jslm_error_image_wrapper">
                                <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/not-login-icon.png">
                            </div>
                        </div>
                        <div class="jslm_error_message">
                            <h1 class="jslm_registration_error jslm_heading_style jslm_font_weight ">'.__('New registration has not been allowed this time','learn-manager').'!...</h1>
                            <h4 class="jslm_registration_error_color jslm_heading_style jslm_no_padding">'.__('Registeration has been disabled by admin.','learn-manager').''.__('Please contact to the administrator for futhur details.','learn-manager').'</h4>
                        </div>
                    </div>
                </div>
            </div>
        ';
        if($return == 1){
            return $html;
        }else{
            echo wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);
        }
    }

    static function getAdminPopupNoRecordFound() {
        $html = '
                <div class="jslearnmanager-popup-norecordfound">
                    <img class="jslm_jsautomessages_image" src="' . JSLEARNMANAGER_PLUGIN_URL.'includes/images/info-icon.png"/>
                    '.__("No record found !","learn-manager").'
                </div>
		';
        echo wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);
    }

    static function getSystemOffline() {
        $offline_text = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('offline_text');
        $html = '
                <div class="jslm_content_wrapper">
                    <div class="jslm_content_data">
                        <div class="jslm_error_message_wrapper">
                            <div class="jslm_error_image">
                                <div class="jslm_error_image_wrapper">
                                    <img class="js_lms_messages_image" src="' . JSLEARNMANAGER_PLUGIN_URL.'includes/images/offline.png"/>
                                </div>
                            </div> 
                            <div class="jslm_error_message">
                                <h1 class="jslm_registration_error jslm_heading_style jslm_font_weight ">'.__('Sorry','learn-manager').'!</h1>
                                <h4 class="jslm_registration_error_color jslm_heading_style jslm_no_padding">' . $offline_text . '</h4>
                            </div>   
                        </div>    
                    </div>    
                </div>
        ';
        echo wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);
    }

    static function getUserGuest($description, $link, $linktext, $image, $return = 0) {
        $html = '<div class="jslm_error_message_wrapper jslm_no_border">
                    <div class="jslm_error_image">
                        <div class="jslm_error_image_wrapper">
                            <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/not-login-icon.png">
                        </div>
                    </div>
                    <div class="jslm_error_message">
                        <h2 class="jslm_permission jslm_no_padding jslm_font_weight">'.__('Sorry','learn-manager').'!</h2>
                        <h2 class="jslm_permission_heading jslm_no_padding jslm_font_weight">'.$description.'</h2>
                        <h5 class="jslm_permission_color jslm_no_padding">'.__('Please login in to perform this action.','learn-manager').'</h5>
                    </div>
                    <a class="error_btn" href="' . $link . '">' . __($linktext,'learn-manager') . '</a>                
                    <a class="error_btn err_register_btn" href="' . esc_url(jslearnmanager::makeUrl(array('jslmsmod'=>'user','jslmslay'=>'register'))) . '">' . __('Register','learn-manager') . '</a>
                </div>';
                    
        return wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);
    }

    static function getUserDisabledMsg() {
        $html='
            <div class="jslm_content_wrapper ">
                <div class="jslm_content_data">
                    <div class="jslm_error_message_wrapper jslm_no_border">
                        <div class="jslm_error_image">
                            <div class="jslm_error_image_wrapper">
                                <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/disable.png">
                            </div>
                        </div>
                        <div class="jslm_error_message">
                            <h2 class="jslm_registration_error jslm_heading_style jslm_font_weight ">' . __('Your account has been disabled, Please contact to the system administrator.', 'learn-manager') . '</h2>
                        </div>

                    </div>
                </div>
            </div>
        ';
        echo wp_kses($html,JSLEARNMANAGER_ALLOWED_TAGS);
    }


    static function setMessageFor($for, $link = null, $linktext = null, $return = 0) {
        $image = null;
        $description = '';
        switch ($for) {
            case '1': // User is guest
                $description = __('You are not logged in', 'learn-manager');
                $html = JSLEARNMANAGERlayout::getUserGuest($description, $link, $linktext, $image, $return);
                break;
            case '3':
                $description = __("Instructor are not allowed to perform this action", 'learn-manager');
                $html = JSLEARNMANAGERlayout::getUserNotAllowed($description, $link, $linktext, $image, $return);
                break;
            case '4':
                $description = __("User are not allowed to perform this action", 'learn-manager');
                $html = JSLEARNMANAGERlayout::getUserNotAllowed($description, $link, $linktext, $image, $return);
                break;
            case '5':
                $description = __("You have already Logged in", 'learn-manager');
                $html = JSLEARNMANAGERlayout::getUserAleadyLoggedIn($description, $link, $linktext, $image, $return);
                break;
            case '6':
                $description = __("You have been disabled by admin", 'learn-manager');
                $html = JSLEARNMANAGERlayout::getUserAleadyLoggedIn($description, $link, $linktext, $image, $return);
                break;    
            case '7': //User has no roles
                $description = __('Please select your role', 'learn-manager');
                $html = JSLEARNMANAGERlayout::getUserNotAllowed($description, $link, $linktext, $image, $return);
                break;    
            case '8':
                $description = __('Not allow to perform action. Account not approve from admin', 'learn-manager');    
                $html = JSLEARNMANAGERlayout::getUserNotAllowed($description, $link, $linktext, $image, $return);
                break;
        }
        return $html;
    }

    static function getUserNotAllowed($description, $link, $linktext, $image, $return = 0) {
        $html = '<div class="jslm_content_wrapper ">
                    <div class="jslm_content_data jslm_text_center">
                        <div class="jslm_error_message_wrapper no-border">
                            <div class="jslm_error_image">
                                <div class="jslm_error_image_wrapper">
                                    <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/not-permission-icon.png">
                                </div>
                            </div>
                            <div class="jslm_error_message">
                                <h2 class="jslm_permission jslm_no_padding jslm_font_weight">'.__('Sorry','learn-manager').'!</h2>
                                <h2 class="jslm_permission_heading jslm_no_padding jslm_font_weight">'.$description.'</h2>
                            ';
                            if($return == 0){    
                                $html .= '<h5 class="jslm_permission_color jslm_no_padding">'.__('For more details,','learn-manager').''.__('Please contact to the administrator.','learn-manager').'</h5>
                            ';
                            }
                            $html .= '</div>';
                            if ($link != null) {
                                $html .= '<a class="button jslm_select_role" href="' . $link . '">' . __($linktext,'learn-manager') . '</a>';
                            }
                        $html .= '</div>';
        if($linktext == null){
            $linktext = "Login";
        }
        
        $html .= '
                    </div>
                </div>
        ';
        return $html;
    }

    static function getUserAleadyLoggedIn($description, $link, $linktext, $image, $return = 0){
        $html = '<div class="jslm_error_message_wrapper jslm_no_border">
                    <div class="jslm_error_image">
                        <div class="jslm_error_image_wrapper">
                            <img src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/already-loggedin.png">
                        </div>
                    </div>
                    <div class="jslm_error_message">
                        <h2 class="jslm_permission_heading jslm_no_padding jslm_font_weight">'.$description.'</h2>
                    </div>
                    <a class="error_btn" href="'.esc_url(jslearnmanager::makeUrl()). '">' . __('Back to home','learn-manager') . '</a>                
                </div>';
                    
        return $html;
    }
}

?>
