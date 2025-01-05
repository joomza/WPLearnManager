<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERPostinstallationModel {

    function updateInstallationStatusConfiguration(){
            $flag = get_option('jslearnmanager_post_installation');
            if($flag == false){
                add_option( 'jslearnmanager_post_installation', '1', '', 'yes' );
            }else{
                update_option( 'jslearnmanager_post_installation', '1');
            }
    }

    function storeConfigurations($data){
        if (empty($data))
            return false;
        $error = false;
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        unset($data['action']);
        unset($data['form_request']);
        unset($data['sampleuserdata']);
        $db = new jslearnmanagerdb();
        foreach ($data as $key => $value) {
            $query = "UPDATE `#__js_learnmanager_config` SET `configvalue` = '" . $value . "' WHERE `configname`= '" . $key . "'";
            $db->setQuery($query);
            if (!$db->query()) {
                $error = true;
            }
        }
        if ($error)
            return JSLEARNMANAGER_SAVE_ERROR;
        else
            return JSLEARNMANAGER_SAVED;
    }

    function getConfigurationValues(){
        $db = new jslearnmanagerdb();
        $this->updateInstallationStatusConfiguration();
        $query = "SELECT configvalue,configname  FROM`#__js_learnmanager_config`";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        $config_array = array();
        foreach ($data as $config) {
            $config_array[$config->configname]=$config->configvalue;
        }
        jslearnmanager::$_data[0] = $config_array;

    }

    function installSampleData($insertsampledata, $temp_data) {

        $date = date('Y-m-d H:i:s');
        $curdate = date('Y-m-d H:i:s');
        $thirdydaydate = date('Y-m-d', strtotime($curdate. ' + 30 days'));


        if ($insertsampledata == 1) {
            $wp_upload_dir = wp_upload_dir();
            global $wpdb;
			
			$query = "SELECT configvalue FROM `" . $wpdb->prefix . "js_learnmanager_config` WHERE configname = 'data_directory'";
			$data_directory = $wpdb->get_var($query);
			
			$src = JSLEARNMANAGER_PLUGIN_PATH ."includes/sample-data/data/";
			$d_d_path = $wp_upload_dir["basedir"]."/".$data_directory;
			JSLEARNMANAGERincluder::getJSModel('common')->makeDir($d_d_path);
			$dist = $wp_upload_dir["basedir"]."/".$data_directory."/data/";
			JSLEARNMANAGERincluder::getJSModel('common')->makeDir($dist);
			$this->recursive_copy($src, $dist);

            /* Course Access Type */
            $query = "SELECT id FROM `" . $wpdb->prefix . "js_learnmanager_course_access_type` WHERE access_type = 'Free'";
            $free = $wpdb->get_var($query);

            $paid = $free;
            if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                $query = "SELECT id FROM `" . $wpdb->prefix . "js_learnmanager_course_access_type` WHERE access_type = 'Paid'";
                $paid = $wpdb->get_var($query);
            }

            $price = 0;
            $currency = 0;
            $isdiscount = 0;
            $discounttype = 0;
            $discount_price = 0;
            $paymentplan_id = 0;
            $isfeatured = 2;
            if(in_array('paymentplan', jslearnmanager::$_active_addons)){
                $paymentplan_id = 1;
            }
            if(in_array('featuredcourse', jslearnmanager::$_active_addons)){
                $isfeatured = 1;
            }
            /* Create new courses */
            // Create Course 1
            if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                $price = '120';
                $currency = '3';
                $isdiscount = '1';
                $discounttype = '1';
                $discount_price = '15';
            }
			$instructor_id = 0;
			$instructor_id1 = 0;
			$instructor_id2 = 0;
            $student_id1 = 0;
            $student_id2 = 0;
            $student_id3 = 0;
            $student_id4 = 0;

            /* insert new student */
            if(isset($_POST['student_id']) && is_numeric($_POST['student_id']) && $_POST['student_id'] != 0){
                $query = "SELECT * FROM `" . $wpdb->prefix . "users` where ID= ". $_POST['student_id'];
                $student_data =$wpdb->get_row($query);
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_user`
                            (id,username,uid,firstname,user_role_id,email,status,created_at)
                              VALUES('','".$student_data->user_login."',".$_POST['student_id'].",'".$student_data->user_login."',2,'".$student_data->user_email."',1,'$date');";
                $wpdb->query($insert_query);
                $student_id = $wpdb->insert_id;
                $student_id1 = $student_id;
                $student_id2 = $student_id;
                $student_id3 = $student_id;
                $student_id4 = $student_id;
            }
            
            /* insert new instructor */
            if(isset($_POST['instructor_id']) && is_numeric($_POST['instructor_id']) && $_POST['instructor_id'] != 0){
                $query = "SELECT * FROM `" . $wpdb->prefix . "users` where ID= ". $_POST['instructor_id'];
                $instructor_data =$wpdb->get_row($query);
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_user`
                            (id,username,uid,firstname,user_role_id,email,status,created_at)
                              VALUES('','".$instructor_data->user_login."',".$_POST['instructor_id'].",'". $instructor_data->user_login ."',1,'". $instructor_data->user_email ."',1,'$date');";
                $wpdb->query($insert_query);
                $instructor_id = $wpdb->insert_id;
                $instructor_id = $instructor_id;
                $instructor_id1 = $instructor_id;
                $instructor_id2 = $instructor_id;
            }
			
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Web Designer','web-design-in-adobe-photoshop','WD-120','',
                            'What is Lorem Ipsum?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem    ',
                            'What is Lorem Ipsum?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                            '','','course.png','1','','',". $price .",". $currency .",". $isdiscount .",". $discounttype .",". $discount_price .",".$paid.",'100','1','1','',".$instructor_id.",'1','','','2','','','0','60 Minutes','1','0','',". $paymentplan_id .",'$date','$date');";
            $wpdb->query($insert_query);
            $course_id1 = $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id1."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id1."";
            $wpdb->query($insert_query);
            //rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x1",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id1);
			rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x1",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id1);

            //Create Course 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','English Literature','english-literature','EL-101','',
                            'What is Lorem Ipsum?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                            'Literature, most generically, is any body of written works. More restrictively, literature refers to writing considered to be an art form or any single writing deemed to have artistic or intellectual value, often due to deploying language in ways that differ from ordinary usage.',
                            '','','course.jpg','1','','','0','0','0','0','0',".$free.",'110','2','3','',".$instructor_id.",'1','','',". $isfeatured .",'$date','$thirdydaydate','0','5 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id2 = $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id2."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id2."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x2",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id2);


            //Create Course 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Unity Game Development','unity-game-development','UG-501','','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                        '','','course.jpg','1','','','0','0','0','0','0',".$free.",'110','2','3','',".$instructor_id1.",'1','','','2','','','0','5 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id3= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id3."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id3."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x3",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id3);

            //Create Course 4
            if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                $price = '100';
                $currency = '3';
                $isdiscount = '1';
                $discounttype = '2';
                $discount_price = '15';
            }
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Writing Skills','writing-skills','WS-150','',
                        'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
                        'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                        '','','course.jpg','1','','',". $price .",". $currency .",". $isdiscount .",". $discounttype .",". $discount_price .",".$paid.",'106','3','3','',".$instructor_id1.",'1','','',". $isfeatured .",'$date','$thirdydaydate','0','10 days','1','0','',".$paymentplan_id.",'$date','$date');";
            $wpdb->query($insert_query);
            $course_id4= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id4."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id4."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x4",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id4);

            //Create Course 5
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Web Design In Adobe Photoshop','web-design-in-adobe-photoshop','AP-110','',
                        'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
                        'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                        '','','course.jpg','1','','','0','0','0','0','0',".$free.",'106','1','4','',".$instructor_id2.",'1','','','2','','','0','10 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id5= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id5."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id5."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x5",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id5);


            //Create Course 6
            if(in_array('paidcourse', jslearnmanager::$_active_addons)){
                $price = '100';
                $currency = '3';
                $isdiscount = '1';
                $discounttype = '2';
                $discount_price = '15';
            }
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Elementary Course In Marketing','elementary-course-in-marketing','CS-110','',
                        'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
                        'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                        '','','course.jpg','1','','',". $price .",". $currency .",'0','0',''0'',".$paid.",'106','1','4','',".$instructor_id2.",'1','','','2','','','0','10 days','1','0',''," . $paymentplan_id . ",'$date','$date');";
            $wpdb->query($insert_query);
            $course_id6= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id6."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id6."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x6",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id6);


            //Create Course 7
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                            VALUES('','Literature Writing','literature-writing','LW-110','','A major in English provides students with a basis from which to continue their studies for a professional degree or to serve as a valuable foundation for careers in teaching.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','','','course.jpg','1','','','0','0','0','0','0',".$free.",'110','2','3','',".$instructor_id.",'1','','',".$isfeatured.",'$date','$thirdydaydate','0','5 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id7= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id7."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id7."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x7",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id7);


            //Create Course 8
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Communication Skills','communication-skills','CS-210','','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                        '','','course.jpg','1','','',". $price .",". $currency .",'0','0','0',".$paid.",'106','1','4','',".$instructor_id2.",'1','','','2','','','0','10 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id8= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id8."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id8."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x8",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id8);


            //Create Course 9
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Interior Design','interior-design','ID-120','','A major in Accounting and Finance (ACF) in B.Sc. (Hons) provides students with a basis from which to continue their studies for a professional degree or to serve as a valuable foundation for careers in business and management.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','','','course.jpg','1','','','0','0','0','0','0',".$free.",'110','2','3','',".$instructor_id.",'1','','',". $isfeatured .",'$date','$thirdydaydate','0','5 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id9= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id9."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id9."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x9",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id9);

            //Create Course 10
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course`(`id`, `title`, `alias`, `course_code`, `subtitle`, `description`, `learningoutcomes`, `meta_description`, `logo`, `logofilename`, `logoisfile`, `file`, `video`, `price`, `currency`, `isdiscount`, `discounttype`, `discount_price`, `access_type`, `category_id`, `course_level`, `language`, `start_date`, `instructor_id`, `course_status`, `keywords`, `expire_date`, `featured`, `startfeatureddate`, `endfeatureddate`, `featuredbyuid`, `course_duration`, `isapprove`, `isdeleted`, `params`, `paymentplan_id`, `created_at`, `updated_at`)
                        VALUES('','Learn English Grammer','learn-english-grammer','CS-110','','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','','','course.jpg','1','','',". $price .",". $currency .",'0','0','0',".$paid.",'106','1','4','',".$instructor_id2.",'1','','','2','','','0','10 days','1','0','','0','$date','$date');";
            $wpdb->query($insert_query);
            $course_id10= $wpdb->insert_id;
            $imagepath = $wp_upload_dir['baseurl']."/".$data_directory."/data/course/course_".$course_id10."/course.jpg";
            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `file`='".$imagepath."' WHERE id=".$course_id10."";
            $wpdb->query($insert_query);
            rename($wp_upload_dir['basedir']."/".$data_directory."/data/course/course_x10",$wp_upload_dir['basedir']."/".$data_directory."/data/course/course_".$course_id10);




            /* categoy bug patch */

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 1 WHERE id=".$course_id1."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 2 WHERE id=".$course_id2."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 3 WHERE id=".$course_id3."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 4 WHERE id=".$course_id4."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 3 WHERE id=".$course_id5."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 2 WHERE id=".$course_id6."";
            $wpdb->query($insert_query);


            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 1 WHERE id=".$course_id7."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 2 WHERE id=".$course_id8."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 3 WHERE id=".$course_id9."";
            $wpdb->query($insert_query);

            $insert_query = "UPDATE `" . $wpdb->prefix . "js_learnmanager_course` SET `category_id`= 4 WHERE id=".$course_id10."";
            $wpdb->query($insert_query);


            /* Create Section */
            // For Course 1 sections
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-I','section-i',".$course_id1.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c1_sectionid1 = $wpdb->insert_id;
            // For course 1 section 1 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c1_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s1_lectureid1 = $wpdb->insert_id;
            // Lecture 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-II','lecture-i',".$c1_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s1_lectureid2 = $wpdb->insert_id;
            // Lecture 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-III','lecture-i',".$c1_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s1_lectureid3 = $wpdb->insert_id;

            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-II','section-ii',".$course_id1.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c1_sectionid2 = $wpdb->insert_id;
            //  For course 1 section 2 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c1_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s2_lectureid1 = $wpdb->insert_id;
            // Lecture 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-II','lecture-i',".$c1_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s2_lectureid2 = $wpdb->insert_id;

            // Section 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-III','section-iii',".$course_id1.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c1_sectionid3 = $wpdb->insert_id;
            //  For course 1 section 3 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c1_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s3_lectureid1 = $wpdb->insert_id;
            // Lecture 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-II','lecture-i',".$c1_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s3_lectureid2 = $wpdb->insert_id;
            // Lecture 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-III','lecture-i',".$c1_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s3_lectureid3 = $wpdb->insert_id;

            // Section 4
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-IV','section-iv',".$course_id1.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c1_sectionid4 = $wpdb->insert_id;
            //  For course 1 section 4 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c1_sectionid4.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c1_s4_lectureid1 = $wpdb->insert_id;

            // For course 2
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-I','section-i',".$course_id2.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c2_sectionid1 = $wpdb->insert_id;
            //  For course 2 section 1
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c2_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c2_s1_lectureid1 = $wpdb->insert_id;
            // Lecture 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-II','lecture-i',".$c2_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c2_s1_lectureid2 = $wpdb->insert_id;

            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-II','section-ii',".$course_id2.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c2_sectionid2 = $wpdb->insert_id;
            //  For course 2 section 2
            // For lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c2_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c2_s2_lectureid1 = $wpdb->insert_id;
            // For lecture 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-II','lecture-i',".$c2_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c2_s2_lectureid2 = $wpdb->insert_id;

            // Section 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-III','section-i',".$course_id2.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c2_sectionid3 = $wpdb->insert_id;
            //  For course 2 section 3 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c2_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c2_s3_lectureid1 = $wpdb->insert_id;

            // For course 3
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-I','section-i',".$course_id3.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c3_sectionid1 = $wpdb->insert_id;
            //  For course 3 section 1 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c3_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c3_s1_lectureid1 = $wpdb->insert_id;

            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-II','section-i',".$course_id3.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c3_sectionid2 = $wpdb->insert_id;
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c3_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c3_s2_lectureid1 = $wpdb->insert_id;
            // Section 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-III','section-i',".$course_id3.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c3_sectionid3 = $wpdb->insert_id;
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c3_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c3_s3_lectureid1 = $wpdb->insert_id;

            //  For course 4
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Lorem ipsum dolor sit amet','section-i',".$course_id4.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c4_sectionid1 = $wpdb->insert_id;
            //  For course 4 section 1 lectures
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c4_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c4_s1_lectureid1 = $wpdb->insert_id;


            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section 2','section-i',".$course_id4.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c4_sectionid2 = $wpdb->insert_id;
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c4_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c4_s2_lectureid1 = $wpdb->insert_id;

            // Section 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section 3','section-i',".$course_id4.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c4_sectionid3 = $wpdb->insert_id;
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c4_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c4_s3_lectureid1 = $wpdb->insert_id;

            //  For course 5
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Lorem ipsum dolor sit amet','section-i',".$course_id5.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c5_sectionid1 = $wpdb->insert_id;

            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c5_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c5_s1_lectureid1 = $wpdb->insert_id;

            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'What are the skills of communication','section-i',".$course_id5.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c5_sectionid2 = $wpdb->insert_id;
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c5_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c5_s2_lectureid1 = $wpdb->insert_id;

            // Section 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section-III','section-i',".$course_id5.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c5_sectionid3 = $wpdb->insert_id;
            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c5_sectionid3.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c5_s3_lectureid1 = $wpdb->insert_id;

            //  For course 6
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section I','section-i',".$course_id6.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c6_sectionid1 = $wpdb->insert_id;

            //  For course 8
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section I','section-i',".$course_id8.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c6_sectionid1 = $wpdb->insert_id;


            //  For course 10
            // Section 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section I','section-i',".$course_id10.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c6_sectionid1 = $wpdb->insert_id;


            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c6_sectionid1.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c6_s1_lectureid1 = $wpdb->insert_id;

            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section II','section-i',".$course_id6.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c6_sectionid2 = $wpdb->insert_id;


            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section II','section-i',".$course_id8.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c8_sectionid2 = $wpdb->insert_id;

            // Section 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section`(`id`, `name`, `alias`, `course_id`, `access_type`, `created_at`, `updated_at`, `section_order`)
                                VALUES('', 'Section II','section-i',".$course_id10.",'','$date','$date',0);";
            $wpdb->query($insert_query);
            $c10_sectionid2 = $wpdb->insert_id;

            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c6_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c6_s2_lectureid1 = $wpdb->insert_id;

            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c8_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c8_s2_lectureid1 = $wpdb->insert_id;

            // Lecture 1
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_section_lecture`(`id`, `name`, `alias`, `section_id`, `description`, `lecture_order`, `params`, `status`, `created_at`, `updated_at`)
                                VALUES('','Lecture-I','lecture-i',".$c10_sectionid2.",'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','0','','1','$date','$date');";
            $wpdb->query($insert_query);
            $c10_s2_lectureid1 = $wpdb->insert_id;



            /*  Data For all lectures */
            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s1_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s1_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_x1",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s1_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s1_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s1_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_x2",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s1_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c1_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s2_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c1_s2_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s2_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c1_s2_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_x2",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s2_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c1_s2_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c1_s2_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s3_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c1_s3_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s3_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c1_s3_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_x4",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id1."/lecture_".$c1_s3_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c1_s3_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c1_s3_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_".$c2_s1_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c2_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_".$c2_s1_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c2_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_x1",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_".$c2_s1_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c2_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c2_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_".$c2_s1_lectureid2."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c2_s1_lectureid2.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_".$c2_s1_lectureid2."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c2_s1_lectureid2.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_x2",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id2."/lecture_".$c2_s1_lectureid2);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c2_s1_lectureid2.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c2_s1_lectureid2.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id3."/lecture_".$c3_s1_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c3_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id3."/lecture_".$c3_s1_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c3_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id3."/lecture_x1",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id3."/lecture_".$c3_s1_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c3_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c3_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            // Files
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id4."/lecture_".$c4_s1_lectureid1."/file-1.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-1.pdf','$path','pdf',".$c4_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $path = $wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id4."/lecture_".$c4_s1_lectureid1."/file-2.pdf";
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','file-2.pdf','$path','pdf',".$c4_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            rename($wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id4."/lecture_x1",$wp_upload_dir["basedir"]."/".$data_directory."/data/course/course_".$course_id4."/lecture_".$c4_s1_lectureid1);
            // Videos
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 1','https://youtu.be/C0DPdy98e4c','video',".$c4_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_lecture_file`(`id`, `filename`, `fileurl`, `file_type`, `lecture_id`, `downloadable`, `created_at`, `updated_at`)
                                VALUES('','Lecture Video 2','https://youtu.be/HzTdxiixjrk','video',".$c4_s1_lectureid1.",'0','$date','$date');";
            $wpdb->query($insert_query);

            if(in_array('quiz',jslearnmanager::$_active_addons)){
                // Lecture Quizzes
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s1_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s1_lectureid2.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s1_lectureid2.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s1_lectureid2.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s2_lectureid2.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s2_lectureid2.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s2_lectureid2.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s4_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s4_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c1_s4_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c2_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c2_s1_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c2_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c2_s2_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c2_s2_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c2_s2_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c6_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c6_s1_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c6_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c6_s2_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c6_s2_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c6_s2_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);

                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c5_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c5_s1_lectureid1.",'".'[{"answer_id":0,"answer":"Option 1","right_answer":0},{"answer_id":1,"answer":"Option 2","right_answer":1},{"answer_id":2,"answer":"Option 3","right_answer":0},{"answer_id":3,"answer":"Option 4","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
                $insert_query = "INSERT INTO `".$wpdb->prefix."js_learnmanager_lecture_quiz_questions`(`id`, `question`, `lecture_id`, `params`, `created_at`, `updated_at`)
                                    VALUES('','Lorem Ipsum is simply dummy text of the printing and typesetting industry.',".$c5_s1_lectureid1.",'".'[{"answer_id":0,"answer":"A","right_answer":1},{"answer_id":1,"answer":"B","right_answer":0},{"answer_id":2,"answer":"C","right_answer":0},{"answer_id":3,"answer":"D","right_answer":0}]'."','$date','$date');";
                $wpdb->query($insert_query);
            }

            //  For enrollment
            // Enrollment 1
			
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id1.",".$course_id1.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment1 = $wpdb->insert_id;
            // Enrollment 2
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id1.",".$course_id2.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment2 = $wpdb->insert_id;
            // Enrollment 3
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id1.",".$course_id3.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment3 = $wpdb->insert_id;
            // Enrollment 4
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id2.",".$course_id2.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment4 = $wpdb->insert_id;
            // Enrollment 5
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id2.",".$course_id4.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment5 = $wpdb->insert_id;
            // Enrollment 6
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id3.",".$course_id2.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment6 = $wpdb->insert_id;
            // Enrollment 7
            $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_student_enrollment`(`id`, `student_id`, `course_id`, `transactionid`, `quiz_result_params`, `lecture_completion_params`, `created_at`, `updated_at`)
                                VALUES('',".$student_id3.",".$course_id1.",'','','','$date','$date');";
            $wpdb->query($insert_query);
            $enrollment7 = $wpdb->insert_id;

            if(in_array('coursereview', jslearnmanager::$_active_addons)){
                //  For Reviews
                // Review 1
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','5',".$student_id1.",".$course_id1.",'$date','$date');";
                $wpdb->query($insert_query);
                // Review 2
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','4',".$student_id1.",".$course_id2.",'$date','$date');";
                $wpdb->query($insert_query);
                // Review 3
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','3',".$student_id1.",".$course_id3.",'$date','$date');";
                $wpdb->query($insert_query);
                // Review 4
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','5',".$student_id2.",".$course_id2.",'$date','$date');";
                $wpdb->query($insert_query);
                // Review 5
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','2',".$student_id2.",".$course_id4.",'$date','$date');";
                $wpdb->query($insert_query);
                // Review 6
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','3',".$student_id3.",".$course_id2.",'$date','$date');";
                $wpdb->query($insert_query);
                // Review 7
                $insert_query = "INSERT INTO `" . $wpdb->prefix . "js_learnmanager_course_reviews`(`id`, `comment`, `reviews`, `student_id`, `course_id`, `created_at`, `updated_at`)
                                    VALUES('','Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.','5',".$student_id3.",".$course_id1.",'$date','$date');";
                $wpdb->query($insert_query);
            }
        }
        if($temp_data == 1){
            $this->installSampleDataTemplate($instructor_id,$course_id1);
        }
        return true;
    }
	function recursive_copy($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while(( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					$this->recursive_copy($src .'/'. $file, $dst .'/'. $file);
				} else {
					copy($src .'/'. $file,$dst .'/'. $file);
				}
			}
		}
		closedir($dir);
	}
    function installSampleDataTemplate($instructor,$course) {

        // Check for the rev slider
        $wp_upload_dir = wp_upload_dir();

        if( ! function_exists("__update_post_meta")){
            function __update_post_meta( $post_id, $field_name, $value = "" ){
                if ( empty( $value ) OR ! $value ){
                    delete_post_meta( $post_id, $field_name );
                }elseif ( ! get_post_meta( $post_id, $field_name ) ){
                    add_post_meta( $post_id, $field_name, $value );
                }else{
                    update_post_meta( $post_id, $field_name, $value );
                }
            }
        }

        if( ! function_exists("uploadPostFeatureImage")){
            function uploadPostFeatureImage($filename,$parent_post_id){
                // Check the type of file. We"ll use this as the "post_mime_type".
                $filetype = wp_check_filetype( basename( $filename ), null );
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    "guid"           => $wp_upload_dir["url"] . "/" . basename( $filename ),
                    "post_mime_type" => $filetype["type"],
                    "post_title"     => preg_replace( "/\.[^.]+$/", "", basename( $filename ) ),
                    "post_content"   => "",
                    "post_status"    => "inherit"
                );
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once( ABSPATH . "wp-admin/includes/image.php" );
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                set_post_thumbnail( $parent_post_id, $attach_id );
            }
        }
        $lm_pages = array();


        // Home
        $new_page_title = "Home";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["home"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["home"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["home"], "_wp_page_template", $new_page_template);
        update_post_meta($lm_pages["home"], "lm_show_header", 2);

        // Home 1
        $new_page_title = "Home 1";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["home1"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["home1"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["home1"], "_wp_page_template", $new_page_template);
        update_post_meta($lm_pages["home1"], "lm_show_header", 2);

        // home 2
        $new_page_title = "Home 2";
        $new_page_template = "templates/template-homepage.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => "",
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["home2"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["home2"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["home2"], "_wp_page_template", $new_page_template);
        update_post_meta($lm_pages["home2"], "lm_show_header", 2);

    /* end of homepages */

    // Price table
        $new_page_title = "Pricing Table";
        $new_page_content = '<div class="lms_hp5_price_box_wrapper"><div class="container"> <div class="row"> <div class="lms_hp5_price_box_wrp"> <div class="lms_hp5_price_box"> <div class="lms_hp5_price_box_top"> <div class="lms_hp5_price_box_top_heading_wrp"> <div class="lms_hp5_price_box_top_heading">Basic Package</div><div class="lms_hp5_price_box_top_sub_heading">Basic Package</div></div></div><div class="lms_hp5_price_box_middle"> <div class="lms_hp5_price_box_middle_row_wrp"> <div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">Buy one for life time</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">24/7 with backup</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">10 users access able</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">1 year backup plan</div></div></div></div><div class="lms_hp5_price_box_bottom"> <div class="lms_hp5_price_box_bottom_price_wrp"> <div class="lms_hp5_price_box_bottom_price">$125</div><div class="lms_hp5_price_box_bottom_choose_plan_wrp" ><a href="#" class="lms_hp5_price_box_bottom_choose_plan">Choose Plan</a></div></div></div></div><div class="lms_hp5_price_box lms_br_color_style2"> <div class="lms_hp5_price_box_top"> <div class="lms_hp5_price_box_top_heading_wrp lms_color_style2"> <div class="lms_hp5_price_box_top_heading">Basic Package</div><div class="lms_hp5_price_box_top_sub_heading">Basic Package</div></div></div><div class="lms_hp5_price_box_middle"> <div class="lms_hp5_price_box_middle_row_wrp"> <div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">Buy one for life time</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">24/7 with backup</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">10 users access able</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">1 year backup plan</div></div></div></div><div class="lms_hp5_price_box_bottom"> <div class="lms_hp5_price_box_bottom_price_wrp"> <div class="lms_hp5_price_box_bottom_price lms_price_color_style2">$125</div><div class="lms_hp5_price_box_bottom_choose_plan_wrp" ><a href="#" class="lms_hp5_price_box_bottom_choose_plan lms_color_style2">Choose Plan</a></div></div></div></div><div class="lms_hp5_price_box lms_br_color_style3"> <div class="lms_hp5_price_box_top"> <div class="lms_hp5_price_box_top_heading_wrp lms_color_style3"> <div class="lms_hp5_price_box_top_heading">Basic Package</div><div class="lms_hp5_price_box_top_sub_heading">Basic Package</div></div></div><div class="lms_hp5_price_box_middle"> <div class="lms_hp5_price_box_middle_row_wrp"> <div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">Buy one for life time</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">24/7 with backup</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">10 users access able</div></div><div class="lms_hp5_price_box_middle_row"> <div class="lms_hp5_price_box_middle_row_text">1 year backup plan</div></div></div></div><div class="lms_hp5_price_box_bottom"> <div class="lms_hp5_price_box_bottom_price_wrp"> <div class="lms_hp5_price_box_bottom_price lms_price_color_style3">$125</div><div class="lms_hp5_price_box_bottom_choose_plan_wrp" ><a href="#" class="lms_hp5_price_box_bottom_choose_plan lms_color_style3">Choose Plan</a></div></div></div></div></div></div></div></div>';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["pricing_table"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["pricing_table"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["pricing_table"], "lm_show_header", 1);
        update_post_meta($lm_pages["pricing_table"], "_wp_page_template", $new_page_template);

    // Learn manager pages
        $page_array[1] = "Add Course";
        $page_array[2] = "Add Lecture";
        $page_array[5] = "Course List";
        $page_array[7] = "Lecture Details";
        $page_array[8] = "Shortlist Courses";
        $page_array[9] = "Instructor Details";
        $page_array[11] = "Student Message";
        $page_array[12] = "Student Profile";
        $page_array[14] = "Instructor Dashboard";
        $page_array[15] = "Instructor Register";
        $page_array[16] = "Login";
        $page_array[17] = "Register";
        $page_array[18] = "Student Dashboard";
        $page_array[19] = "Student Register";
        $page_array[20] = "Thank You";
        $page_array[21] = "Dashboard";
        $page_array[24] = "Instructor Messages";
        $page_array[31] = "All Instructors";
        $page_array[32] = "Categories List";
        $page_array[33] = "Course Search";



        foreach ($page_array as $key => $value) {
            // $value_string = strtolower($value);
            // $value_string = sanitize_title($value_string);
            $value_string = strtolower($value);
            $value_string = str_replace(" ","_",$value_string);
            $new_page_title = $value;
            $new_page_content = '[vc_row][vc_column][jslearnmanager page page="'.$key.'"][/vc_column][/vc_row]';
            $new_page_template = "templates/template-fullwidth.php";
            $page_check = get_page_by_title($new_page_title);
            $new_page = array(
                    "post_type" => "page",
                    "post_title" => $new_page_title,
                    "post_content" => $new_page_content,
                    "post_status" => "publish",
                    "post_author" => 1,
                    "post_parent" => 0,
            );
            if(!isset($page_check->ID)){
                $lm_pages[$value_string] = wp_insert_post($new_page);
            }else{
                $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
                $lm_pages[$value_string] = wp_insert_post($new_page);
            }
            update_post_meta($lm_pages[$value_string], "lm_show_header", 1);

            if($key == 'Lecture Details')
            {
                update_post_meta($lm_pages[$value_string], "_wp_page_template",'templates/template-without-header-footer.php');
            }else {
                update_post_meta($lm_pages[$value_string], "_wp_page_template",$new_page_template);
            }

        }
    // Learn manager pages end
        // News & Rumors
        $new_page_title = "News & Rumors";
        $new_page_content = '[vc_row][vc_column][lm_news_and_rumors_new style="4" posts_per_page="6"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["news_and_rumors"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["news_and_rumors"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["news_and_rumors"], "lm_show_header", 1);
        update_post_meta($lm_pages["news_and_rumors"], "_wp_page_template", $new_page_template);


        // FAQ
        $new_page_title = "FAQ";
        $new_page_content = '[vc_row][vc_column][vc_tta_accordion active_section="1" el_class="jsjb-jm-faq-wrap"][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754286724-c9ed48ed-c7f7" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754537433-695717d6-6bfc" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754536332-73927004-0a28" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754535606-7dffd7a9-2119" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754534808-bb9dfb79-6d18" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754534095-ec21098b-4397" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754529612-21aedc15-ddd9" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][vc_tta_section title="Lorem Ipsum is simply dummy text of the printing and typesetting industry." tab_id="1482754528056-93a9c873-2efd" el_class="jsjb-jm-faq"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_tta_section][/vc_tta_accordion][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["faq"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["faq"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["faq"], "lm_show_header", 1);
        update_post_meta($lm_pages["faq"], "_wp_page_template", $new_page_template);


       // Our Team
        $new_page_title = "Our Team";
        $new_page_content = '[vc_row][vc_column][lm_team_members style="3" per_row="3" posts_per_page="3"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["ourteam"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["ourteam"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["ourteam"], "_wp_page_template", $new_page_template);
        update_post_meta($lm_pages["ourteam"], "lm_show_header", 1);


        // Thank You Page
        $new_page_title = "Thank You";
        $new_page_content = '[vc_row][vc_column][jslearnmanager page page="20"][/vc_column][/vc_row]';
        $new_page_template = "templates/template-fullwidth.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["thankyou"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["thankyou"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["thankyou"], "_wp_page_template", $new_page_template);
        update_post_meta($lm_pages["thankyou"], "lm_show_header", 1);


        // Contact Us
        $new_page_title = "Contact Us";
        $new_page_content = "";
        $new_page_template = "templates/template-contactus.php";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        if(!isset($page_check->ID)){
            $lm_pages["contact_us"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["contact_us"] = wp_insert_post($new_page);
        }
        update_post_meta($lm_pages["contact_us"], "_wp_page_template", $new_page_template);
        update_post_meta($lm_pages["contact_us"], "lm_show_header", 1);


        // Blog Page
        $new_page_title = "Blog";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "page",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        if(!isset($page_check->ID)){
            $lm_pages["blog"] = wp_insert_post($new_page);
        }else{
            $new_page["post_title"] = "JS Learn Manager ".$new_page_title;
            $lm_pages["blog"] = wp_insert_post($new_page);
        }
        update_option("page_for_posts", $lm_pages["blog"]);


    // Update home page contents

        //Home
        $new_page_content = '[vc_row][vc_column][lm_top_banner style="1" jslearnmanagerpageid="'.$lm_pages["course_list"].'"][/vc_column][/vc_row][vc_row][vc_column][lm_feature_box][/vc_column][/vc_row][vc_row][vc_column][lm_about_us style="1"][/vc_column][/vc_row][vc_row][vc_column][lm_courses style="1" coursetype="1" posts_per_page="10" jslearnmanagerpageid1="'.$lm_pages["course_list"].'"][/vc_column][/vc_row][vc_row][vc_column][lm_share_knowlege_register style="1"][/vc_column][/vc_row][vc_row][vc_column][lm_team_members per_row="3" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][lm_share_knowlege_faqs][/vc_column][/vc_row][vc_row][vc_column][lm_news_and_rumors_new style="2"][/vc_column][/vc_row]';

        $my_post = array(
            "ID"           => $lm_pages["home"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );

        //Home 1
        $new_page_content = '[vc_row][vc_column][lm_top_banner style="2" category1title="2" category2title="4" category3title="10" category4title="9" category5title="5" jslearnmanagerpageid="'.$lm_pages["course_list"].'"][/vc_column][/vc_row][vc_row][vc_column][lm_courses style="2" coursetype="1" posts_per_page="10" jslearnmanagerpageid1="'.$lm_pages["course_list"].'" cat_title_1="1" cat_title_2="1" cat_title_3="1" cat_title_4="1" cat_title_5="1"][/vc_column][/vc_row][vc_row][vc_column][lm_team_members style="2" per_row="3" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][lm_instructor_student_register][/vc_column][/vc_row][vc_row][vc_column][lm_testimonials per_row="3" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][lm_news_and_rumors_new style="5"][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $lm_pages["home1"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );

        //Home 2
        $new_page_content = '[vc_row][vc_column][lm_top_banner style="3" jslearnmanagerpageid="'.$lm_pages["course_list"].'" category_3_1_title="2" icon1="fa fa-desktop" category_3_2_title="4" icon2="fa fa-flask" category_3_3_title="10" icon3="fa fa-subscript" category_3_4_title="9" icon4="fa fa-trophy" category_3_5_title="5" icon5="fa fa-language"][/vc_column][/vc_row][vc_row][vc_column][lm_three_box_features][/vc_column][/vc_row][vc_row][vc_column][lm_courses style="3" coursetype="1" posts_per_page="10" jslearnmanagerpageid1="'.$lm_pages["course_list"].'"][/vc_column][/vc_row][vc_row][vc_column][lm_share_knowlege_register style="2"][/vc_column][/vc_row][vc_row][vc_column][lm_team_members style="2" per_row="3" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][lm_statistics][/vc_column][/vc_row][vc_row][vc_column][lm_news_and_rumors_new style="5"][/vc_column][/vc_row]';
        $my_post = array(
            "ID"           => $lm_pages["home2"],
            "post_content" => $new_page_content,
        );
        wp_update_post( $my_post );


        // Update WP Options
        $wp_page_array = array();
        foreach ($page_array as $key => $value) {
            $value_string = strtolower($value);
            $value_string = str_replace(" ","_",$value_string);
            $wp_page_array[$value_string] = $lm_pages[$value_string];
        }
        update_option("learn-manager-layout", $wp_page_array);
        // ----------------Posts -------- //
        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
        $new_page_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed sapien non elit rhoncus faucibus id mattis metus. Integer in dictum lectus. Cras et risus leo. Morbi viverra congue sem vel posuere. Aenean odio turpis, posuere ac sem id, posuere viverra nisi. Integer pellentesque ornare tortor, ut suscipit leo sagittis vitae. In eu porta nisi. In id odio non risus blandit ultricies ut aliquam ex.Curabitur at ante pulvinar, mattis ipsum sit amet, aliquam turpis. Vivamus et sem mollis, ornare odio nec, consequat leo. Quisque commodo eget velit vitae sagittis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas cursus augue at enim fringilla, eget egestas risus hendrerit. In hac habitasse platea dictumst. Nulla vitae enim id odio porttitor mollis. In vehicula finibus eleifend. Aenean gravida, nisl ac dapibus tincidunt, mi orci pretium sapien, eu varius magna nunc egestas metus.Vivamus vitae rhoncus mi, vel ultrices dolor. Mauris vitae ex laoreet, sagittis dolor id, commodo mauris. Integer pellentesque mi non dictum vehicula. Sed a elit velit. Suspendisse vel justo sed enim gravida iaculis. Nulla pretium a odio non convallis. Donec lacus lectus, ultrices vel elit vel, auctor laoreet odio. Donec velit est, consectetur ac condimentum eu, scelerisque in elit. Sed ultricies quis enim id congue. Aliquam nec aliquam urna. Ut iaculis vel purus nec pellentesque. Proin quis lorem eros. Praesent lacinia id sapien sed elementum. Fusce sodales nisl orci, ac venenatis erat tincidunt faucibus. Cras elementum efficitur lorem eu pellentesque. Donec efficitur fringilla arcu ac.  ';
        $new_page = array(
                "post_type" => "post",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/post4.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // ----------------Custom posts -------- //


        // News & Rumors
        $new_page_title = "Free Advertising For Your Online Business";
        $new_page_content = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for "lorem ipsum" will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).';
        $new_page = array(
                "post_type" => "lm_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Attract More Attention Sales And Profits";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<strong></strong>';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Top fun activities tips for you to do";
        $new_page_content = '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_news_and_rumors",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        $filename = $wp_upload_dir["basedir"]."/2017/01/nar_3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);


        // Team members
        $new_page_title = "Brody Frey";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "lm_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Front-end Developer");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_4.png";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Janae May";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "lm_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Project Manager");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Kaley James";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "lm_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Team Leader");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Marlee Donovan";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
        $new_page = array(
                "post_type" => "lm_team_member",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "team_member_title", "Cheif executive office / CEO");
        update_post_meta($new_page_id, "team_member_facebook", "http://www.facebook.com");
        update_post_meta($new_page_id, "team_member_twitter", "http://www.twitter.com");
        update_post_meta($new_page_id, "team_member_linkedin", "http://www.linkedin.com");
        update_post_meta($new_page_id, "team_member_gplus", "http://www.googleplus.com");
        update_post_meta($new_page_id, "team_member_instagram", "http://www.instagram.com");
        update_post_meta($new_page_id, "team_member_pinterest", "http://www.pinterest.com");
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir["basedir"]."/2017/01/tm_1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // Price Table
        $new_page_title = "Basic Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "lm_price" , "$ 500");
        __update_post_meta($new_page_id, "lm_line1" , "50 Courses Create");
        __update_post_meta($new_page_id, "lm_line2" , "Unlimited Students Enroll");
        __update_post_meta($new_page_id, "lm_line3" , "Unlimited Access");
        __update_post_meta($new_page_id, "lm_line4" , "Featured Course 10");
        __update_post_meta($new_page_id, "lm_buynowlink" , "#");
        __update_post_meta($new_page_id, "lm_subtitle" , "For Intermediate");


        $new_page_title = "Business Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "lm_price" , "$ 750");
        __update_post_meta($new_page_id, "lm_line1" , "Buy one for life time");
        __update_post_meta($new_page_id, "lm_line2" , "24/7 with backup");
        __update_post_meta($new_page_id, "lm_line3" , "10 users access able");
        __update_post_meta($new_page_id, "lm_line4" , "1 year backup plan");
        __update_post_meta($new_page_id, "lm_buynowlink" , "#");
        __update_post_meta($new_page_id, "lm_subtitle" , "For Professional");

        $new_page_title = "Complete Package";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_price_table",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        __update_post_meta($new_page_id, "lm_price" , "$ 1200");
        __update_post_meta($new_page_id, "lm_line1" , "Buy one for life time");
        __update_post_meta($new_page_id, "lm_line2" , "24/7 with backup");
        __update_post_meta($new_page_id, "lm_line3" , "10 users access able");
        __update_post_meta($new_page_id, "lm_line4" , "1 year backup plan");
        __update_post_meta($new_page_id, "lm_buynowlink" , "#");
        __update_post_meta($new_page_id, "lm_subtitle" , "Exceptional");



        // TESTIMONIAL
        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis quam sit amet dolor fermentum, in porta nisi egestas. Nullam convallis laoreet gravida. Pellentesque sed.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );

        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_1.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Naro MathDoe";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at tempus velit. Aliquam et diam convallis, tempus ligula ut, placerat sem. Nulla condimentum nulla a.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_2.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "MARY DOE";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce accumsan vitae massa vel aliquet. Morbi sed nibh eget lectus consequat tempor. Aliquam erat volutpat. Nam.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_3.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Robert Lafore";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eleifend lacinia enim at dapibus. Nam eget accumsan neque. Nam felis augue, egestas ut varius vel.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);

        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_4.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        $new_page_title = "Auro Navanth";
        $new_page_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam commodo laoreet neque, vitae facilisis quam eleifend a. In consectetur purus quis arcu dictum, sit amet.";
        $new_page_template = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_testimonials",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $new_page_id = wp_insert_post($new_page);
        update_post_meta($new_page_id, "_wp_page_template", $new_page_template);
        // set feature image
        $filename = $wp_upload_dir["basedir"]."/2017/01/tsti_5.jpg";
        $parent_post_id = $new_page_id;
        uploadPostFeatureImage($filename,$parent_post_id);

        // Pages and custom post are created Now create Menu ----------------

        update_option( "page_on_front", $lm_pages["home"] );
        update_option( "show_on_front", "page" );

        // MENU
        // Check if the menu exists
        $menu_name = "Learn Manager";
        $menu_exists = wp_get_nav_menu_object( $menu_name );


        // Custom menu widget 2
        $new_page_title = "Latest Courses";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_menu_widget",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $widget2_id = wp_insert_post($new_page);
        update_post_meta($widget2_id, "lm_menu_widget", 3);
        __update_post_meta($widget2_id, "lm_mw_widget" , 1);
        __update_post_meta($widget2_id, "lm_mw_coursetype" , 4);
        __update_post_meta($widget2_id, "lm_mw_imgmust" , 1);
        __update_post_meta($widget2_id, "lm_mw_numberofrecords" , "3");

         // Custom menu widget 3
        $new_page_title = "Register as a Student";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_menu_widget",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $widget3_id = wp_insert_post($new_page);
        update_post_meta($widget3_id, "lm_menu_widget", 4);
        __update_post_meta($widget3_id, "lm_mw_widget" , 13);
        __update_post_meta($widget3_id, "widget_type" , 1);
        __update_post_meta($widget3_id, "jslm_lm_widget_image_URL_two", JSLEARNMANAGER_PLUGIN_URL.'includes/images/register-student-2.png');
        __update_post_meta($widget3_id, "lm_mw_register_btn_link" , jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'studentregister','jslearnmanagerpageid'=>$lm_pages["course_list"])));
        __update_post_meta($widget3_id, "lm_mw_register_btn_title" , "Register As A Student");
        __update_post_meta($widget3_id, "lm_mw_banner_description" , "JS Learn Manager is one of the best LMS plugin for wordpress to learn online course anytime and anywhere. Offering unlimited Free and Paid courses with multiple lectures including files, videos and quizes.");



         // Custom menu widget 3
        $new_page_title = "Register as a Instructor";
        $new_page_content = "";
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                "post_type" => "lm_menu_widget",
                "post_title" => $new_page_title,
                "post_content" => $new_page_content,
                "post_status" => "publish",
                "post_author" => 1,
                "post_parent" => 0,
        );
        $widget4_id = wp_insert_post($new_page);
        update_post_meta($widget4_id, "lm_menu_widget", 5);
        __update_post_meta($widget4_id, "lm_mw_widget" , 13);
        __update_post_meta($widget4_id, "widget_type" , 1);
        __update_post_meta($widget4_id, "jslm_lm_widget_image_URL_two", JSLEARNMANAGER_PLUGIN_URL.'includes/images/register-instructor-3.png');
       __update_post_meta($widget4_id, "lm_mw_register_btn_link" , jslearnmanager::makeUrl(array('jslmsmod'=>'user', 'jslmslay'=>'instructorregister','jslearnmanagerpageid'=>$lm_pages["course_list"])));
        __update_post_meta($widget4_id, "lm_mw_register_btn_title" , "Register As A Instructor");
        __update_post_meta($widget4_id, "lm_mw_banner_description" , "The best online platform to sell courses Paid and Free. Create unlimited courses and publish publically to earn with your skills and capabilities of teaching. Grab Students with offering discounts on courses and manymore. Add lectures and quizes too. ");



        // If it doesn"t exist, let"s create it.
        if( !$menu_exists){
            $menu_id = wp_create_nav_menu($menu_name);

            $locations = get_theme_mod("nav_menu_locations");
            $locations["primary"] = $menu_id;
            set_theme_mod( "nav_menu_locations", $locations );

            $itemData =  array(
                "menu-item-object-id" => $lm_pages["home"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_home = wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-object-id" => $lm_pages["home1"],
                "menu-item-parent-id" => $parent_home,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-object-id" => $lm_pages["home2"],
                "menu-item-parent-id" => $parent_home,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);


            // Course
            $itemData = array (
                "menu-item-title" => "Courses",
                "menu-item-object-id" => $lm_pages["course_list"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish",
                "menu-item-classes"    => "lms-course-menu",
                "menu-item-numberofsubcolumns" => 4,
                "menu-item-submenuheadning" => "Courses"
            );

            $parent_courses = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $parent_courses, '_menu_item_numberofsubcolumns', 4 );
            update_post_meta( $parent_courses, '_menu_item_submenuheadning', "Courses" );

            $itemData =  array(
                "menu-item-title" => "Course List",
                "menu-item-object-id" => $lm_pages["course_list"],
                "menu-item-parent-id" => $parent_courses,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Shortlist Course",
                "menu-item-object-id" => $lm_pages["shortlist_courses"],
                "menu-item-parent-id" => $parent_courses,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Search Course",
                "menu-item-object-id" => $lm_pages["course_search"],
                "menu-item-parent-id" => $parent_courses,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

             $itemData =  array(
                "menu-item-title" => "Free Course",
                "menu-item-object-id" => $lm_pages["course_list"],
                "menu-item-parent-id" => $parent_courses,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

             $itemData =  array(
                "menu-item-title" => "Categories List",
                "menu-item-object-id" => $lm_pages["categories_list"],
                "menu-item-parent-id" => $parent_courses,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-title" => "Latest Courses",
                "menu-item-object-id" => $widget2_id,
                "menu-item-parent-id" => $parent_courses,
                "menu-item-object" => "lm_menu_widget",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns" => 3,
                "menu-item-status"    => "publish"
            );
            $submenuid = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $submenuid, '_menu_item_numberofsubcolumns', 3 );


            // Student

            $itemData =  array(
                "menu-item-title" => "Student",
                "menu-item-object-id" => $lm_pages["student_dashboard"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-classes"    => "lms-student-menu",
                "menu-item-numberofsubcolumns" => 4,
                "menu-item-status"    => "publish"
            );
            $parent_student = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $parent_student, '_menu_item_numberofsubcolumns', 4 );

            $itemData =  array(
                "menu-item-title" => "Student Dashboard",
                "menu-item-object-id" => $lm_pages["student_dashboard"],
                "menu-item-parent-id" => $parent_student,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Course Search",
                "menu-item-object-id" => $lm_pages["course_search"],
                "menu-item-parent-id" => $parent_student,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            if(jslearnmanager::checkAddonActiveOrNot('message')){
                $itemData =  array(
                    "menu-item-title" => "Messages",
                    "menu-item-object-id" => $lm_pages["student_message"],
                    "menu-item-parent-id" => $parent_student,
                    "menu-item-object" => "page",
                    "menu-item-type"      => "post_type",
                    "menu-item-status"    => "publish"
                );
                wp_update_nav_menu_item($menu_id, 0, $itemData);
            }

            $itemData =  array(
                "menu-item-title" => "My Profile",
                "menu-item-object-id" => $lm_pages["student_profile"],
                "menu-item-parent-id" => $parent_student,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "All Instructors",
                "menu-item-object-id" => $lm_pages["all_instructors"],
                "menu-item-parent-id" => $parent_student,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Register As a Student",
                "menu-item-object-id" => $widget3_id,
                "menu-item-parent-id" => $parent_student,
                "menu-item-object" => "lm_menu_widget",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns" => 3,
                "menu-item-status"    => "publish"
            );
            $submenuid = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $submenuid, '_menu_item_numberofsubcolumns', 3 );

            // instructor
            $itemData =  array(
                "menu-item-title" => "Instructor",
                "menu-item-object-id" => $lm_pages["instructor_dashboard"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-classes"    => "lms-student-menu",
                "menu-item-numberofsubcolumns" => 4,
                "menu-item-status"    => "publish"
            );
            $parent_instructor = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $parent_instructor, '_menu_item_numberofsubcolumns', 4 );
            $itemData =  array(
                "menu-item-title" => "Instructor Dashboard",
                "menu-item-object-id" => $lm_pages["instructor_dashboard"],
                "menu-item-parent-id" => $parent_instructor,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Add Course",
                "menu-item-object-id" => $lm_pages["add_course"],
                "menu-item-parent-id" => $parent_instructor,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            $itemData =  array(
                "menu-item-title" => "Course Search",
                "menu-item-object-id" => $lm_pages["course_search"],
                "menu-item-parent-id" => $parent_instructor,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

            if(jslearnmanager::checkAddonActiveOrNot('message')){
                $itemData =  array(
                    "menu-item-title" => "Message",
                    "menu-item-object-id" => $lm_pages["instructor_messages"],
                    "menu-item-parent-id" => $parent_instructor,
                    "menu-item-object" => "page",
                    "menu-item-type"      => "post_type",
                    "menu-item-status"    => "publish"
                );
                wp_update_nav_menu_item($menu_id, 0, $itemData);
            }
            $itemData =  array(
                "menu-item-title" => "My Profile",
                "menu-item-object-id" => $lm_pages["instructor_details"],
                "menu-item-parent-id" => $parent_instructor,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

             $itemData =  array(
                "menu-item-title" => "Register As a Instructor",
                "menu-item-object-id" => $widget4_id,
                "menu-item-parent-id" => $parent_instructor,
                "menu-item-object" => "lm_menu_widget",
                "menu-item-type"      => "post_type",
                "menu-item-numberofsubcolumns" => 3,
                "menu-item-status"    => "publish"
            );
            $submenuid = wp_update_nav_menu_item($menu_id, 0, $itemData);
            update_post_meta( $submenuid, '_menu_item_numberofsubcolumns', 3 );

            $itemData =  array(
                "menu-item-title" => "Other Pages",
                "menu-item-object-id" => $lm_pages["blog"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            $parent_pages = wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $lm_pages["news_and_rumors"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $lm_pages["faq"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $lm_pages["all_instructors"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $lm_pages["pricing_table"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);
            $itemData =  array(
                "menu-item-object-id" => $lm_pages["contact_us"],
                "menu-item-parent-id" => $parent_pages,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);

             $itemData =  array(
                "menu-item-title" => "Login",
                "menu-item-object-id" => $lm_pages["login"],
                "menu-item-parent-id" => 0,
                "menu-item-object" => "page",
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
              );
            wp_update_nav_menu_item($menu_id, 0, $itemData);


        }
            $widget_positions = get_option("sidebars_widgets");
        // Woocommerce sidebar
            $widget_positions["woocommerce-sidebar"][] = "woocommerce_widget_cart-1";
            $widget_woocommerce_widget_cart_array[1] = array("title" => "My Cart");
            $widget_woocommerce_widget_cart_array["_multiwidget"] = 1;
            // Left sidebar
            $widget_positions["left-sidebar"][] = "search-1";
            $search_array[1] = array("title" => "Search");
            $search_array["_multiwidget"] = 1;
            $widget_positions["left-sidebar"][] = "recent-posts-1";
            $recent_posts_array[1] = array("title" => "Recent Posts", "number" => 5);
            $recent_posts_array["_multiwidget"] = 1;
            $widget_positions["left-sidebar"][] = "recent-comments-1";
            $recent_comments_array[1] = array("title" => "Recent Comments", "number" => 5);
            $recent_comments_array["_multiwidget"] = 1;
            $widget_positions["left-sidebar"][] = "archives-1";
            $archives_array[1] = array("title" => "Archives");
            $archives_array["_multiwidget"] = 1;
            $widget_positions["left-sidebar"][] = "categories-1";
            $categories_array[1] = array("title" => "Categories");
            $categories_array["_multiwidget"] = 1;
            $widget_positions["left-sidebar"][] = "meta-1";
            $meta_array[1] = array("title" => "Meta");
            $meta_array["_multiwidget"] = 1;
            // Right sidebar
            $widget_positions["right-sidebar"][] = "calendar-1";
            $calendar_array[1] = array("title" => "Calendar");
            $calendar_array["_multiwidget"] = 1;
            $widget_positions["right-sidebar"][] = "widget_lm_recent_comments-1";
            $widget_lm_recent_comments_array[1] = array("title" => "JS Learn Manager Recent Comments", "count" => 2);
            $widget_lm_recent_comments_array["_multiwidget"] = 1;
            $widget_positions["right-sidebar"][] = "widget_lm_recent_posts-1";
            $widget_lm_recent_posts_array[1] = array("title" => "JS Learn Manager Recent Posts", "category" => "");
            $widget_lm_recent_posts_array["_multiwidget"] = 1;
            $widget_positions["right-sidebar"][] = "nav_menu-1";
            $nav_menu_array[1] = array("title" => "Custom Menu", "nav_menu" => "");
            $nav_menu_array["_multiwidget"] = 1;
            $widget_positions["right-sidebar"][] = "pages-1";
            $pages_array[1] = array("title" => "Pages", "sortby" => "post_title");
            $pages_array["_multiwidget"] = 1;
            $widget_positions["right-sidebar"][] = "tag_cloud-1";
            $tag_cloud_array[1] = array("title" => "Tag Cloud", "taxonomy" => "post_tag");
            $tag_cloud_array["_multiwidget"] = 1;
            $widget_positions["right-sidebar"][] = "text-1";
            $text_array[1] = array("title" => "Text Heading", "text" => "Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum Text body is here for lorem ipsum");
            $text_array["_multiwidget"] = 1;
            // News and rumors
            $widget_positions["news_and_rumors"][] = "search-2";
            $search_array[2] = array("title" => "Search");
            $search_array["_multiwidget"] = 1;
            $widget_positions["news_and_rumors"][] = "recent-posts-2";
            $recent_posts_array[2] = array("title" => "Recent Posts", "number" => 5);
            $recent_posts_array["_multiwidget"] = 1;



            // footer1
            $widget_positions["footer1"][] = "widget_lm_footeraboutus-1";
            $widget_lm_footeraboutus_array[1] = array("title" => "JS Learn Manager", "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.");
            $widget_lm_footeraboutus_array["_multiwidget"] = 1;


            // footer2
            $widget_positions["footer2"][] = "widget_lm_footerlmslinks-1";
            $widget_lm_footerlmslinks_array[1] = array(
                "titlestudent" => "Student Links",
                "title1"=>"Student Dashboard", "link1"=> get_the_permalink($lm_pages["student_dashboard"]),
                "title2"=>"Shortlist Courses", "link2"=> get_the_permalink($lm_pages["shortlist_courses"]),
                "title3"=>"Course Search", "link3"=> get_the_permalink($lm_pages["course_search"]),
                "title5"=>"Profile", "link5"=> get_the_permalink($lm_pages["student_profile"]),

                "titleinstructor" => "Instructor Links",
                "title6"=>"Instructor Dashboard", "link6"=> get_the_permalink($lm_pages["instructor_dashboard"]),
                "title7"=>"Add Courses", "link7"=> get_the_permalink($lm_pages["add_course"]),
                "title9"=>"My Profile", "link9"=> get_the_permalink($lm_pages["instructor_details"]),
                "title10"=>"Course Search", "link10"=> get_the_permalink($lm_pages["course_search"]),

                );
            if(jslearnmanager::checkAddonActiveOrNot('quiz')){
                $widget_lm_footerlmslinks_array[1]['title4'] = "Message";
                $widget_lm_footerlmslinks_array[1]['link4'] = get_the_permalink($lm_pages["student_message"]);
                $widget_lm_footerlmslinks_array[1]['title8'] = "Message";
                $widget_lm_footerlmslinks_array[1]['link8'] = get_the_permalink($lm_pages["instructor_messages"]);
            }
            $widget_lm_footerlmslinks_array["_multiwidget"] = 1;


            update_option("widget_"."widget_woocommerce_widget_cart"  , $widget_woocommerce_widget_cart_array);
            update_option("widget_"."search"  , $search_array);
            update_option("widget_"."recent-posts"  , $recent_posts_array);
            update_option("widget_"."recent-comments"  , $recent_comments_array);
            update_option("widget_"."archives"  , $archives_array);
            update_option("widget_"."categories"  , $categories_array);
            update_option("widget_"."meta"  , $meta_array);
            update_option("widget_"."calendar"  , $calendar_array);
            update_option("widget_"."widget_lm_recent_comments"  , $widget_lm_recent_comments_array);
            update_option("widget_"."widget_lm_recent_posts"  , $widget_lm_recent_posts_array);
            update_option("widget_"."nav_menu"  , $nav_menu_array);
            update_option("widget_"."pages"  , $pages_array);
            update_option("widget_"."tag_cloud"  , $tag_cloud_array);
            update_option("widget_"."text"  , $text_array);
            update_option("widget_"."widget_lm_footeraboutus"  , $widget_lm_footeraboutus_array);
            update_option("widget_"."widget_lm_footerlmslinks"  , $widget_lm_footerlmslinks_array);

            // update this array at last
            update_option( "sidebars_widgets" , $widget_positions);

            global $wpdb;
            $pageid = $wpdb->get_var("Select id FROM `" . $wpdb->prefix . "posts` WHERE post_name = 'learn-manager-controlpanel'");
            if(is_numeric($pageid) && $pageid > 0){
                update_post_meta($pageid, "_wp_page_template", "templates/template-fullwidth.php");
            }
        // Update the configuration default page to Courses
            $query = "UPDATE `".$wpdb->prefix."js_learnmanager_config` SET configvalue = '".$lm_pages["course_list"]."' WHERE configname = 'default_pageid'";
            $wpdb->query($query);
            update_option("rewrite_rules", "");
          return 1;

    }


    function getPageList() {
        $db = new jslearnmanagerdb();
        $query = "SELECT ID AS id, post_title AS text FROM `#__posts` WHERE post_type = 'page' AND post_status = 'publish' ";
        $db->setQuery($query);
        $pages = $db->loadObjectList();
        return $pages;
    }

    function getWpUsersList() {
        global $wpdb;
        $query = "SELECT id,CONCAT(user_login,' ( ',display_name,' ) - ',id) AS text  FROM `" . $wpdb->prefix . "users`";
        $users = $wpdb->get_results($query);
        $data[0] = (object) array('id' => 0, 'text' => __('Select User', 'learn-manager'));
        foreach ($users as $user) {
            $data[] = $user;
        }
        return $data;
    }


}?>
