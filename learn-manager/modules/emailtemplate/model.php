<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGEREmailtemplateModel {

    function sendMail($mailfor, $action, $id) {
        if (!is_numeric($mailfor))
            return false;
        if (!is_numeric($action))
            return false;
        if ($id != null)
            if (!is_numeric($id))
                return false;
        $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('email');
        $senderEmail = $config_array['mailfromaddress'];
        $senderName = $config_array['mailfromname'];
        $adminEmailid = $config_array['adminemailaddress'];

        // $isguest = JSLEARNMANAGERincluder::getObjectClass('user')->isguest();
        $pageid = jslearnmanager::getPageid();
        switch ($mailfor) {
            case 1: // Course
                switch ($action) {
                    case 1: // Add New Course

                        $record = $this->getRecordByTablenameAndId('js_learnmanager_course', $id);
                        if($record == ''){
                            return;
                        }
                        $username = $record->name;
                        $coursename = $record->title;
                        $email = $record->email;
                        $status = $record->course_status;
                        $approve = $record->isapprove;
                        $category = $record->category_name;
                        $access_type = $record->access_type;
                        $checkstatus = null;
                        $publishstatus = null;
                        $link = null;

                        if($status == 1){
                            $publishstatus = "Published";
                        }else{
                            $publishstatus = "Unpublished";
                        }

                        if ($approve == 1) {
                            $checkstatus = __('Approved', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Course Detail', 'learn-manager') . '</a>';
                        }
                        if ($approve == 0) {
                            $checkstatus = __('Pending', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'user', 'jslmslay'=>'dashboard')) .'" target="_blank">' . __('Dashboard', 'learn-manager') . '</a>';
                        }
                        if ($approve == -1) {
                            $checkstatus = __('Rejected', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'user', 'jslmslay'=>'dashboard')) .'" target="_blank">' . __('Dashboard', 'learn-manager') . '</a>';
                        }

                        $matcharray = array(
                            '{COURSE_TITLE}' => $coursename,
                            '{INSTRUCTOR_NAME}' => $username,
                            '{COURSE_LINK}' => $link,
                            '{COURSE_STATUS}' => $checkstatus,
                            '{COURSE_CATEGORY}' => $category,
                            '{ACCESS_TYPE}' => $access_type,
                            '{PUBLISHED_STATUS}' => $publishstatus
                        );


                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_course');
                        $template = $this->getTemplateForEmail('new-course');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        // Add New course mail to Instructor

                        if ($getEmailStatus->instructor == 1) {
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }

                        if ($getEmailStatus->admin == 1) {
                            $template = $this->getTemplateForEmail('new-course-admin');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $link = '<a href="' . admin_url("admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$id) .'" target="_blank">' . __('View Course', 'learn-manager') . '</a>';
                            $matcharray['{COURSE_LINK}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);

                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        break;
                    case 2: // Course Delete

                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('delete_course');
                        if ($getEmailStatus->instructor == 1) {
                            $matcharray = array(
                                '{INSTRUCTOR_NAME}' => sanitize_key($_SESSION['learnmanager-email']['instructorname']),
                                '{COURSE_TITLE}' => sanitize_key($_SESSION['learnmanager-email']['coursetitle'])
                            );
                            $email = sanitize_key($_SESSION['learnmanager-email']['useremail']);
                            $template = $this->getTemplateForEmail('delete-course');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);

                            unset($_SESSION['learnmanager-email']);
                            // course Delete mail to User
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        break;
                    case 3: // Course approve OR reject

                        $record = $this->getRecordByTablenameAndId('js_learnmanager_course', $id);
                        if($record == ''){
                            return;
                        }
                        $username = $record->name;
                        $coursename = $record->title;
                        $email = $record->email;
                        $status = $record->course_status;
                        $approve = $record->isapprove;
                        $category = $record->category_name;
                        $access_type = $record->access_type;
                        $checkstatus = null;
                        $publishstatus = null;
                        $link = null;

                        if ($approve == 1 && $status == 1) {
                            $checkstatus = __('Approved', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Course Detail', 'learn-manager') . '</a>';
                        }
                        if($approve == 1 && $status == 0){
                            $checkstatus = __('Approved', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Edit Course', 'learn-manager') . '</a>';
                        }
                        if ($approve == -1) {
                            $checkstatus = __('Rejected', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Edit Course', 'learn-manager') . '</a>';
                        }

                        if($status == 1){
                            $publishstatus = "Published";
                        }else{
                            $publishstatus = "Unpublished";
                        }
                        $matcharray = array(
                            '{COURSE_TITLE}' => $coursename,
                            '{INSTRUCTOR_NAME}' => $username,
                            '{COURSE_LINK}' => $link,
                            '{COURSE_STATUS}' => $checkstatus,
                            '{COURSE_CATEGORY}' => $category,
                            '{ACCESS_TYPE}' => $access_type,
                            '{PUBLISHED_STATUS}' => $publishstatus
                        );

                        $template = $this->getTemplateForEmail('course-status');
                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('course_status');

                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        // course Approve mail to User
                        if ($getEmailStatus->instructor == 1) {
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        break;
                    case 4: // course approve OR reject for featured

                        $record = $this->getRecordByTablenameAndId('js_learnmanager_course', $id);
                        if($record == ''){
                            return;
                        }
                        $username = $record->name;
                        $coursename = $record->title;
                        $email = $record->email;
                        $status = $record->course_status;
                        $approve = $record->isapprove;
                        $category = $record->category_name;
                        $access_type = $record->access_type;
                        $checkstatus = null;
                        $publishstatus = null;
                        $link = null;
                        $featuredcourse = $record->featured;
                        $checkfeatured_course = null;

                        if ($featuredcourse == -1) {
                            $checkfeatured_course = __('rejected for featured', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Edit Course', 'learn-manager') . '</a>';
                        }
                        if ($featuredcourse == 1) {
                            $checkfeatured_course = __('approved for featured', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Edit Course', 'learn-manager') . '</a>';
                        }
                        if ($featuredcourse == 2) {
                            $checkfeatured_course = __('removed for featured', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Edit Course', 'learn-manager') . '</a>';
                        }
                        if ($featuredcourse == 0) {
                            $checkfeatured_course = __('pending for featured', 'learn-manager');
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'editcourse', 'jslearnmanagerid'=>$id)) .'" target="_blank">' . __('Edit Course', 'learn-manager') . '</a>';
                        }

                        $matcharray = array(
                            '{COURSE_TITLE}' => $coursename,
                            '{INSTRUCTOR_NAME}' => $username,
                            '{COURSE_LINK}' => $link,
                            '{COURSE_STATUS}' => $checkfeatured_course,
                            '{COURSE_CATEGORY}' => $category,
                            '{ACCESS_TYPE}' => $access_type,
                            '{PUBLISHED_STATUS}' => $publishstatus
                        );
                        $template = $this->getTemplateForEmail('featured-course-status');
                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('feature_course_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        // course featured mail to Instructor
                        if ($getEmailStatus->instructor == 1) {
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        break;
                    }
                break;
            case 2: // For new enrollment
                switch($action){
                    case 1: // Enroll in course
                        $record = $this->getRecordByTablenameAndId('student_enrollment', $id);
                        if($record == ''){
                            return;
                        }
                        $link = null;
                        $studentname = $record->studentname;
                        $instructorname = $record->instructorname;
                        $studentemail = $record->studentemail;
                        $instructoremail = $record->instructoremail;
                        $coursename = $record->title;
                        $access_type = $record->access_type;
                        if($access_type == "Free"){
                            $course_price = 0;
                        }else{
                            $course_price = $record->symbol." ".$record->price;
                        }

                        $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'course', 'jslmslay'=>'coursedetails', 'jslearnmanagerid'=>$record->course_id)) .'" target="_blank">' . __('Course Detail', 'learn-manager') . '</a>';
                        $instructorprofile = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'instructor', 'jslmslay'=>'instructordetail', 'jslearnmanagerid'=>$record->instructorid)) .'" target="_blank">' . __('Instructor Detail', 'learn-manager') . '</a>';
                        $studentprofile = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'student', 'jslmslay'=>'studentprofile', 'jslearnmanagerid'=>$record->studentid)) .'" target="_blank">' . __('Student Profile', 'learn-manager') . '</a>';
                        $matcharray = array(
                            '{COURSE_TITLE}' => $coursename,
                            '{STUDENT_NAME}' => $studentname,
                            '{COURSE_LINK}' => $link,
                            '{INSTRUCTOR_PROFILE}' => $instructorprofile,
                            '{INSTRUCTOR_NAME}' => $instructorname,
                            '{STUDENT_PROFILE}' => $studentprofile,
                            '{ACCESS_TYPE}' => $access_type,
                            '{COURSE_PRICE}' =>  $course_price
                        );
                        $template = $this->getTemplateForEmail('courseenrollment-student');
                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('course_enrollment');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        // course enrollment mail to student
                        if ($getEmailStatus->student == 1) {
                            $this->sendEmail($studentemail, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }

                        $template = $this->getTemplateForEmail('courseenrollment-instructor');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        // course enrollment mail to instructor
                        if ($getEmailStatus->instructor == 1) {
                            $this->sendEmail($instructoremail, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        // course enrollment mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $template = $this->getTemplateForEmail('courseenrollment-admin');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $link = '<a href="' . admin_url("admin.php?page=jslm_course&jslmslay=coursedetail&jslearnmanagerid=".$record->course_id) .'" target="_blank">' . __('Course Detail', 'learn-manager') . '</a>';
                            $matcharray['{COURSE_LINK}'] = $link;
                            $studentprofile = '<a href="' . admin_url("admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid=".$record->studentid) .'" target="_blank">' . __('Student Profile', 'learn-manager') . '</a>';
                            $matcharray['{STUDENT_PROFILE}'] = $studentprofile;
                            $instructorprofile = '<a href="' . admin_url("admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=".$record->instructorid) .'" target="_blank">' . __('Instructor Profile', 'learn-manager') . '</a>';
                            $matcharray['{INSTRUCTOR_PROFILE}'] = $instructorprofile;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);

                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                    break;
                }
            break;
            case 3: //user resgistration
                switch ($action) {
                    case 1: //user register registration
                        $record = $this->getRecordByTablenameAndId('users', $id);
                        if($record == ''){
                            return;
                        }
                        $link = null;

                        $Username = $record->username;
                        $email = $record->useremail;
                        $role = $record->userrole;
                        if($role == 'Student'){
                            $dashboard = 'studentdashboard';
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'student', 'jslmslay'=>$dashboard)) .'" target="_blank">' . __('Dashboard', 'learn-manager') . '</a>';
                        }elseif($role == 'Instructor'){
                            $dashboard  = 'instructordashboard';
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'instructor', 'jslmslay'=>$dashboard)) .'" target="_blank">' . __('Dashboard', 'learn-manager') . '</a>';
                        }

                        $matcharray = array(
                            '{USER_NAME}' => $Username,
                            '{MY_DASHBOARD_LINK}' => $link,
                            '{USER_ROLE}' => $role
                        );

                        $template = $this->getTemplateForEmail('new-user');
                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_user');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        // New lms registration mail to instructor
                        if ($getEmailStatus->instructor == 1 && $role == "Instructor") {
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        // New lms registration mail to student
                        if ($getEmailStatus->student == 1 && $role == "Student") {
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        if($getEmailStatus->admin == 1){
                            $template = $this->getTemplateForEmail('new-user-admin');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            if($role == 'Student'){
                                $dashboard = 'studentdetail';
                                $studentid = JSLEARNMANAGERincluder::getJSModel('student')->getStudentId($id);
                                $link = '<a href="' . admin_url("admin.php?page=jslm_student&jslmslay=studentdetail&jslearnmanagerid=".$studentid) .'" target="_blank">' . __('Student Profile', 'learn-manager') . '</a>';
                            }elseif($role == 'Instructor'){
                                $dashboard  = 'instructordetail';
                                $instructorid = JSLEARNMANAGERincluder::getJSModel('instructor')->getInstructorId($id);
                                $link = '<a href="' . admin_url("admin.php?page=jslm_instructor&jslmslay=instructordetail&jslearnmanagerid=".$instructorid) .'" target="_blank">' . __('Instructor Profile', 'learn-manager') . '</a>';
                            }
                            $matcharray['{MY_DASHBOARD_LINK}'] = $link;
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                    break;
                }
            break;
            case 4: // Payouts
                switch($action){
                    case 1:
                        $record = $this->getRecordByTablenameAndId('payout',$id);
                        if($record == ''){
                            return;
                        }
                        $link = null;
                        $instructorname = $record->name;
                        $payoutdate = $record->payoutdate;
                        $amount = $record->payment;
                        $file = '<a href="' . $record->file .'" target="_blank">' . __('Attachment', 'learn-manager') . '</a>';
                        $email = $record->email;
                        $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'instructor', 'jslmslay'=>'instructordashboard')) .'" target="_blank">' . __('Dashboard', 'learn-manager') . '</a>';
                        if($file == null){
                            $file = 'No Attachment';
                        }
                        $matcharray = array(
                            '{INSTRUCTOR_NAME}' => $instructorname,
                            '{PAYOUT_AMOUNT}' => $amount,
                            '{PAYOUT_DATE}' => $payoutdate,
                            '{PAYOUT_LINK}' => $link,
                            '{PAYOUT_FILE_LINK}' => $file
                        );

                        $template = $this->getTemplateForEmail('payout-email-instructor');
                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('payout');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // payout to instructor
                        if ($getEmailStatus->instructor == 1) {
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        // payout to admin
                        if ($getEmailStatus->admin == 1) {
                            $template = $this->getTemplateForEmail('payout-email-admin');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $link = '<a href="' . admin_url("admin.php?page=jslm_payouts") .'" target="_blank">' . __('Payouts', 'learn-manager') . '</a>';
                            $matcharray['{PAYOUT_LINK}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);

                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                    break;
                }
            break;
            case 5: // Send message
                switch($action){
                    case 1:
                        $record = $this->getRecordByTablenameAndId('message',$id);
                        if($record == ''){
                            return;
                        }
                        if($record == ''){
                            return;
                        }
                        $link = null;
                        $message = $record->message;
                        $messagesubject = $record->subject;
                        $senddate = $record->created_at;
                        $sendby = $record->role;
                        $msgsenderemail = $record->email;
                        $email = '';
                        $sendername = '';
                        $receviername = '';
                        if($sendby == "Student"){
                            $email = $record->instructoremail;
                            $sendername = $record->studentname;
                            $receviername = $record->instructorname;
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'message', 'jslmslay'=>'messageconversation', 'jslearnmanagerid'=>$record->instructor_uid, 'jslearnmanagermsgid'=>$id)) .'" target="_blank">' . __('Message', 'learn-manager') . '</a>';
                        }
                        elseif($sendby == "Instructor"){
                            $email = $record->studentemail;
                            $sendername = $record->instructorname;
                            $receviername = $record->studentname;
                            $link = '<a href="' . jslearnmanager::makeUrl(array('jslearnmanagerpageid'=>jslearnmanager::getPageid(), 'jslmsmod'=>'message', 'jslmslay'=>'messageconversation', 'jslearnmanagerid'=>$record->student_uid, 'jslearnmanagermsgid'=>$id)) .'" target="_blank">' . __('Message', 'learn-manager') . '</a>';
                        }
                        $matcharray = array(
                            '{RECEIVER_NAME}'=>$receviername,
                            '{SENDER_NAME}' =>$sendername,
                            '{SENDER_EMAIL_ADDRESS}' =>$msgsenderemail,
                            '{MESSAGE}' => $message,
                            '{SENDER_ROLE}' => $sendby
                        );
                        $template = $this->getTemplateForEmail('message-to-sender');
                        $getEmailStatus = JSLEARNMANAGERincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('message');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        if($getEmailStatus->instructor == 1 && $sendby == "Student"){
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        if($getEmailStatus->student == 1 && $sendby == "Instructor"){
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                    break;
                }
            break;
        }
    }


    function getTemplate($tempfor) {
        if(!$tempfor)
            return '';

        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_emailtemplates` WHERE templatefor = '" . $tempfor . "'";
        $db->setQuery($query);
        jslearnmanager::$_data[0] = $db->loadObject();
        return;
    }

    function storeEmailTemplate($data) {
        if (empty($data))
            return false;

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $data['body'] = wpautop(wptexturize(stripslashes($data['body'])));
        $row = JSLEARNMANAGERincluder::getJSTable('emailtemplate');
        if (!$row->bind($data)) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSLEARNMANAGER_SAVE_ERROR;
        }

        return JSLEARNMANAGER_SAVED;
    }

    function getTemplateForEmail($templatefor) {
        $db = new jslearnmanagerdb();
        $query = "SELECT * FROM `#__js_learnmanager_emailtemplates` WHERE templatefor = '" . $templatefor . "'";
        $db->setQuery($query);
        $template = $db->loadObject();
        return $template;
    }

    function replaceMatches(&$string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = str_replace($find, $replace, $string);
        }
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '') {
        if (!$senderName)
            $senderName = jslearnmanager::$_config['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', array($this,'jslm_set_html_content_type'));
        $body = preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        wp_mail($recevierEmail, $subject, $body, $headers, $attachments);
    }



    function jslm_set_html_content_type() {
        return 'text/html';
    }

    // function sendEmail($recemail,$sub,$message,$senderemail,$sendername,$mailfor){
    //     // $myfile = fopen("sendEmailing.html", "a") or die("Unable to open file!");
    //     // $txt  = "<hr>\n</br>";
    //     // $txt .= $mailfor."\n</br></br>";
    //     // $txt .= "Sender name => ". $sendername."\n</br>";
    //     // $txt .= "Sender Email => ". $senderemail."\n</br>";
    //     // $txt .= "Recepient Email => ". $recemail."\n\n</br></br>";
    //     // $txt .= "Message => \n</br>". $message."\n\n</br></br>";
    //     // $txt .= "Subject => ". $sub."\n</br>";
    //     // $txt .= "<hr>\n</br></br></br></br>";
    //     // fwrite($myfile, $txt);
    //     // fclose($myfile);
    // }

    function getRecordByTablenameAndId($tablename, $id, $creditid = null) {
        if (!is_numeric($id))
            return false;
        $query = null;
        $db = new jslearnmanagerdb();
        switch ($tablename) {
            case 'js_learnmanager_course':
                $query = "SELECT c.id, c.title, c.instructor_id, c.course_status, c.isapprove, c.price, i.name, u.email , u.id as user_id, category.category_name, accesstype.access_type, l.language, clevel.level, c.featured
                    FROM `#__js_learnmanager_course` AS c
                    JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                    JOIN `#__js_learnmanager_user` AS u ON u.id = i.user_id
                    LEFT JOIN `#__js_learnmanager_category` AS category ON category.id = c.category_id
                    LEFT JOIN `#__js_learnmanager_course_access_type` AS accesstype ON accesstype.id = c.access_type
                    LEFT JOIN `#__js_learnmanager_language` AS l ON l.id = c.language
                    LEFT JOIN `#__js_learnmanager_course_level` AS clevel ON clevel.id = c.course_level
                    WHERE c.id = ".$id;
                break;
            case 'js_lms_paymenthistory':
                $query = apply_filters( 'jslm_paidcourse_get_query_for_email_template', '' , $id);
                break;
            case 'users':
                $query = 'SELECT u.name AS username, u.email AS useremail, r.role AS userrole
                            FROM `#__js_learnmanager_user` AS u
                            JOIN `#__js_learnmanager_user_role` AS r ON r.id = u.user_role_id
                            WHERE u.id = ' . $id;
                break;
            case 'student_enrollment':
                $query ='SELECT c.title, c.id as course_id, CONCAT(su.firstname," ",su.lastname) as studentname, su.email as studentemail, s.id as studentid, iu.email as instructoremail, CONCAT(iu.firstname," ",iu.lastname) as instructorname, i.id as instructorid, ac.access_type, currency.symbol, c.price
                            FROM `#__js_learnmanager_course` AS c
                            JOIN `#__js_learnmanager_student_enrollment` AS sc ON c.id = sc.course_id
                            JOIN `#__js_learnmanager_student` AS s ON s.id = sc.student_id
                            JOIN `#__js_learnmanager_user` AS su ON su.id = s.user_id
                            LEFT JOIN `#__js_learnmanager_instructor` AS i ON i.id = c.instructor_id
                            JOIN `#__js_learnmanager_user` AS iu ON iu.id = i.user_id
                            LEFT JOIN `#__js_learnmanager_course_access_type` AS ac ON c.access_type = ac.id
                            LEFT JOIN `#__js_learnmanager_currencies` AS currency ON c.currency = currency.id
                            WHERE sc.id ='.$id;
                break;
            case 'payout':
                $query = apply_filters( 'jslm_payouts_get_payout_query_for_email', '' , $id);
                break;
            case 'message':
                $query = apply_filters( 'jslm_message_email_query_data', '', $id);
                break;
        }
        if ($query != null) {
            $db = new jslearnmanagerdb();
            $db->setQuery($query);
            $record = $db->loadObject();
            return $record;
        }
        return false;
    }

    function getMessagekey(){
        $key = 'emailtemplate';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }
}

?>
