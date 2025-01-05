<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERMessages {
    /*
     * setLayoutMessage
     * @params $message = Your message to display
     * @params $type = Messages types => 'updated','error','update-nag'
     */

    public static $counter;

    public  static function setLayoutMessage($message, $type,$msgkey) {
        JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable($message,$type,'notification',$msgkey);
    }

    public static function getLayoutMessage($msgkey) {
        $frontend = (is_admin()) ? '' : 'frontend';
        $divHtml = '';
        $notificationdata = JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->getNotificationDatabySessionId('notification',$msgkey,true);
        if (isset($notificationdata['msg'][0]) && isset($notificationdata['type'][0])) {
            for ($i = 0; $i < COUNT($notificationdata['msg']); $i++){
                if(is_admin()){
                    if(isset($notificationdata['type'][$i])){
                        $divHtml .= '<div class="frontend ' . $notificationdata['type'][$i] . '"><p>' . $notificationdata['msg'][$i] . '</p></div>';
                    }
                }else{
                    if(isset($notificationdata['type'][$i])){
                        if($notificationdata['type'][$i] == 'updated'){
                            $alert_class = 'success';
                            $img_name = 'cou-alert-successful.png';
                        }elseif($notificationdata['type'][$i] == 'saved'){
                            $alert_class = 'success';
                            $img_name = 'cou-alert-successful.png';
                        }elseif($notificationdata['type'][$i] == 'saved'){
                            //$alert_class = 'info';
                            //$alert_class = 'warning';
                        }elseif($notificationdata['type'][$i] == 'error'){
                            $alert_class = 'danger';
                            $img_name = 'cou-alert-unsuccessful.png';
                        }elseif($notificationdata['type'][$i] == 'pending'){
                            $alert_class = 'warning';
                            $img_name = 'cou-alert-unsuccessful.png';
                        }
                        $divHtml .= '<div class="alert alert-' . $alert_class . '" role="alert" id="autohidealert">
                                        <img class="leftimg" src="'.JSLEARNMANAGER_PLUGIN_URL.'includes/images/'.$img_name.'" />
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        '. $notificationdata['msg'][$i] . '
                                    </div>';
                    }
                }
            }
        }
        echo wp_kses($divHtml,JSLEARNMANAGER_ALLOWED_TAGS);
    }

    public static function getMSelectionEMessage() { // multi selection error message
        return __('Please first make a selection from the list', 'learn-manager');
    }

    public static function getMessage($result, $entity) {
        $msg['message'] = __('Unknown');
        $msg['status'] = "updated";
        $msg1 = JSLEARNMANAGERmessages::getEntityName($entity);
        switch ($result) {
            case 'RECAPTCHA_FAILED':
                $msg['message'] = __('Invalid Answered', 'learn-manager');
                $msg['status'] = 'error';
                break;
            case JSLEARNMANAGER_INVALID_REQUEST:
                $msg['message'] = __('Invalid Request', 'learn-manager');
                $msg['status'] = 'error';
                break;
            case JSLEARNMANAGER_SAVED:
                $msg2 = __('has been saved successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_SAVE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been saved successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_DELETED:
                if($entity == 'user')
                    $msg2 = __('has been removed from our system successfully', 'learn-manager');
                else
                    $msg2 = __('has been deleted successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_NOT_EXIST:
                $msg['status'] = "error";
                $msg['message'] = __('Record does not Exist', 'learn-manager');
                break;
            case JSLEARNMANAGER_DELETE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('can not be delete', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSLEARNMANAGERmessages::$counter) {
                        if(JSLEARNMANAGERmessages::$counter > 1){
                            $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case JSLEARNMANAGER_PUBLISHED:
                $msg2 = __('has been published successfully', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSLEARNMANAGERmessages::$counter) {
                        if(JSLEARNMANAGERmessages::$counter > 1){
                            $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case JSLEARNMANAGER_PUBLISH_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been published successfully', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSLEARNMANAGERmessages::$counter) {
                            $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case JSLEARNMANAGER_UN_PUBLISHED:
                $msg2 = __('has been unpublished successfully', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSLEARNMANAGERmessages::$counter) {
                        if(JSLEARNMANAGERmessages::$counter > 1){
                            $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case JSLEARNMANAGER_UN_PUBLISH_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been unpublished successfully', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSLEARNMANAGERmessages::$counter) {
                            $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case JSLEARNMANAGER_REQUIRED:
                $msg['message'] = __('Fields has been set as a required successfully', 'learn-manager');
                break;
            case JSLEARNMANAGER_REQUIRED_ERROR:
                $msg['status'] = "error";
                if (JSLEARNMANAGERmessages::$counter) {
                    if (JSLEARNMANAGERmessages::$counter == 1)
                        $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . __('Field has not been set as a required successfully', 'learn-manager');
                    else
                        $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . __('Fields has not been set as a required successfully', 'learn-manager');
                }else {
                    $msg['message'] = __('Field has not been set as a required successfully', 'learn-manager');
                }
                break;
            case JSLEARNMANAGER_NOT_REQUIRED:
                $msg['message'] = __('Fields has been set as a not required successfully', 'learn-manager');
                break;
            case JSLEARNMANAGER_NOT_REQUIRED_ERROR:
                $msg['status'] = "error";
                if (JSLEARNMANAGERmessages::$counter) {
                    if (JSLEARNMANAGERmessages::$counter == 1)
                        $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . __('Field has not been set as a not required successfully', 'learn-manager');
                    else
                        $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . __('Fields has not been set as a not required successfully', 'learn-manager');
                }else {
                    $msg['message'] = __('Field has not been set as a not required successfully', 'learn-manager');
                }
                break;
            case JSLEARNMANAGER_ORDER_UP:
                $msg['message'] = __('Field has been set ordered up successfully', 'learn-manager');
                break;
            case JSLEARNMANAGER_ORDER_UP_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('Field has not been set ordered up successfully', 'learn-manager');
                break;
            case JSLEARNMANAGER_ORDER_DOWN:
                $msg['message'] = __('Field has been set ordered down successfully', 'learn-manager');
                break;
            case JSLEARNMANAGER_ORDER_DOWN_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('Field has not been set ordered down successfully', 'learn-manager');
                break;
            case JSLEARNMANAGER_REJECTED:
                $msg2 = __('has been rejected successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_REJECT_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been rejected successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_APPROVED:
                $msg2 = __('has been approved successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_APPROVE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been approved successfully', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (JSLEARNMANAGERmessages::$counter) {
                        $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case JSLEARNMANAGER_SET_DEFAULT:
                $msg2 = __('has been set as default', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_UNPUBLISH_DEFAULT_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('Unpublished field cannot be set as a default', 'learn-manager');
                break;
            case JSLEARNMANAGER_SET_DEFAULT_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been set as default', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_STATUS_CHANGED:
                $msg2 = __('status has been updated successfully', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_STATUS_CHANGED_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been updated', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_IN_USE:
                $msg['status'] = "error";
                $msg2 = __('in use cannot be delete', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_ALREADY_EXIST:
                $msg['status'] = "error";
                if($msg1 == "Lecture"){
                    $msg2 = __('video url already exist.', 'learn-manager');
                }else{
                    $msg2 = __('name already exist', 'learn-manager');
                }
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                if (JSLEARNMANAGERmessages::$counter) {
                    $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                }
                break;
            case JSLEARNMANAGER_ALREADY_ENROLLED:
                $msg['status'] = "error";
                $msg2 = __('already enrolled', 'learn-manager');
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case JSLEARNMANAGER_FILE_TYPE_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('File type error', 'learn-manager');
                break;
            case JSLEARNMANAGER_FILE_SIZE_ERROR:
                $msg['status'] = "error";
                $msg['message'] = __('File size error', 'learn-manager');
                break;
            case JSLEARNMANAGER_ENABLED:
                $msg['status'] = "updated";
                $msg2 = __('has been enabled', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
            case JSLEARNMANAGER_ENABLE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been enabled', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
            case JSLEARNMANAGER_DISABLED:
                $msg['status'] = "updated";
                $msg2 = __('has been disabled', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
            case JSLEARNMANAGER_DISABLE_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been disabled', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
            case JSLEARNMANAGER_WOO_UNPUBLISHED:
                $msg['status'] = "updated";
                $msg['message'] = __('Credits pack has been saved and unpublished successfully. You should also disable package from woo commerce if you have already.', 'learn-manager');
                break;
            case JSLEARNMANAGER_ENROLLED:
                $msg['status'] = "updated";
                $msg2 = __('You have successfully enrolled in ');
                if($msg1){
                    $msg['message'] = $msg2 . ' ' . strtolower($msg1);
                }
                break;
            case JSLEARNMANAGER_SEND:
                $msg['status'] = "updated";
                $msg2 = __('has been sent successfully!');
                if($msg1){
                    $msg['message'] = $msg1. ' ' .$msg2;
                }
                break;
            case JSLEARNMANAGER_SEND_ERROR:
                $msg['status'] = "error";
                $msg2 = __('has not been sent successfully!');
                if($msg1){
                    $msg['message'] = $msg1. ' ' .$msg2;
                }
                break;
            case JSLEARNMANAGER_EMPTY_MESSAGE:
                $msg['status'] = "error";
                $msg2 = __('Empty message cannot be sent');
                if($msg1){
                    $msg['message'] = $msg2;
                }
                break;
            case JSLEARNMANAGER_PENDING:
                $msg['status'] = "pending";
                $msg2 = __('Waiting for approval!');
                if($msg1){
                    $msg['message'] = $msg2;
                }
                break;
            case JSLEARNMANAGER_LIMIT_EXCEED:
                $msg['status'] = "error";
                $msg2 = __('File limit has been exceeded', 'learn-manager');
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                if (JSLEARNMANAGERmessages::$counter) {
                    $msg['message'] = JSLEARNMANAGERmessages::$counter . ' ' . $msg['message'];
                }
                break;
            case JSLEARNMANAGER_COURSE_APPROVAL_PENDING:
                $msg['status'] = 'pending';
                $msg2 = __('approval pending due to account not approved by admin', 'learn-manager');
                if($msg1){
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
        }
        return $msg;
    }

    static function getEntityName($entity) {
        $name = "";
        $entity = strtolower($entity);
        switch ($entity) {
            case 'course':$name = __('Course', 'learn-manager');
                break;
            case 'lecture':$name = __('Lecture', 'learn-manager');
                break;
            case 'featuredcourse':$name = __('Featured Course' , 'learn-manager');
                break;
            case 'section':$name = __('Course Section' , 'learn-manager');
                break;
            case 'review':$name = __('Reviews' , 'learn-manager');
                break;
            case 'message':$name = __('Message', 'learn-manager');
                break;
            case 'payout':$name = __('Payout', 'learn-manager');
                break;
            case 'student':$name = __('Student', 'learn-manager');
                break;
            case 'instructor':$name = __('Instructor', 'learn-manager');
                break;
            case 'record':$name = __('Record', 'learn-manager');
                break;
            case 'paymentplan':$name = __('Payment Plan', 'learn-manager');
                break;
            case 'category':$name = __('Category', 'learn-manager');
                break;
            case 'language':$name = __('Course Language', 'learn-manager');
                break;
            case 'courselevel':$name = __('Course Level', 'learn-manager');
                break;
            case 'user':$name = __('User', 'learn-manager');
                break;
            case 'profile':$name = __('Profile', 'learn-manager');
                break;
            case 'fieldordering':$name = __('Field Ordering', 'learn-manager');
                break;
            case 'configuration':$name = __('Configuration', 'learn-manager');
                break;
            case 'paymenthistory':$name = __('Payment History', 'learn-manager');
                break;
            case 'paymentmethodconfiguration':$name = __('Payment Method Configuration', 'learn-manager');
                break;
            case 'emailtemplate':$name = __('Email Template', 'learn-manager');
                break;
            case 'awards':$name = __('Awards', 'learn-manager');
                break;
            case 'country':$name = __('Country', 'learn-manager');
                break;
            case 'shortlistcourse':$name = __('Shortlist Course', 'learn-manager');
                break;
            case 'userrole':$name = __('User role', 'learn-manager');
                break;
            case 'customfield':$name = __('Custom Field' , 'learn-manager');
                break;
            case 'slug':$name = __('Slug' , 'learn-manager');
                break;
            case 'prefix':$name = __('Prefix' , 'learn-manager');
                break;
            case 'currency':$name = __('Currency', 'learn-manager');
                break;
        }
        return $name;
    }

}

?>
