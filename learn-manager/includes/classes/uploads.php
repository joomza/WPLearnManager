<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERUploads {

    private $uploadfor;
    private $objectid;
    private $parentid;
    private $counter = 0;
    function jslearnmanager_upload_dir( $dir ) {
        $datadirectory = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $file = @fopen($dir['basedir']."/index.html", 'w');
        if($file){
            fclose($file);
        }
        $file = @fopen($dir['basedir'].'/'.$datadirectory."/index.html", 'w');
        if($file){
            fclose($file);
        }
        $path = $datadirectory . '/data';
        $file = @fopen($dir['basedir'].'/'.$path."/index.html", 'w');
        if($file){
            fclose($file);
        }
        $file = @fopen($dir['basedir'].'/'.$path.'/'.$this->uploadfor."/index.html", 'w');
        if($file){
            fclose($file);
        }
        $path = $datadirectory . '/data';
        $flag = false;
        if($this->uploadfor == 'course'){
            $path = $path . '/course/course_'.$this->objectid;
        }elseif($this->uploadfor == 'lecture'){
            $path = $path . '/course/course_'.$this->parentid.'/lecture_'.$this->objectid;
        }elseif($this->uploadfor == 'profile'){
            $path = $path . '/profile/profile_'.$this->objectid;
        }elseif ($this->uploadfor == 'payouts') {
            $path = $path . '/payouts/payout_' .$this->objectid;
        }elseif($this->uploadfor == 'awards'){
            $flag = true;
            $path = $path . '/awards/award_'.$this->objectid;
        }elseif($this->uploadfor == 'category'){
            $flag = true;
            $path = $path . '/category/category_'.$this->objectid;
        }
        $userpath = $path;

        $array = array(
            'path'   => $dir['basedir'] . '/' . $userpath,
            'url'    => $dir['baseurl'] . '/' . $userpath,
            'subdir' => '/'. $userpath,
        ) + $dir;

        // delete previous files
        if($flag && $this->counter == 0){
            $path = $array['path'];
            $files = glob( $path . '/*');
            foreach($files as $file){
                if(is_file($file)) unlink($file);
            }
        }
        $file = @fopen($dir['basedir'].'/'.$path."/index.html", 'w');
        if($file){
            fclose($file);
        }
        $this->counter++;
        return $array;
    }

    function learnManagerUpload($id, $parentid = 0, $file,$for = 0){
        $file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_file_size');
        if (!function_exists('wp_handle_upload')) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }
        if($for == 0){
            $this->objectid = $id;
            $this->uploadfor = 'course';
            $model = 'course';
        }elseif($for == 2){
            $this->objectid = $id;
            $this->parentid = $parentid;
            $this->uploadfor = 'lecture';
            $model = 'lecture';
        }elseif($for == 3){
            $this->objectid = $id;
            $this->uploadfor = 'profile';
            $model = 'user';
        }elseif($for == 4){
            $this->objectid = $id;
            $this->uploadfor = 'payouts';
            $model = 'payouts';
        }elseif($for == 5){
            $this->objectid = $id;
            $this->uploadfor = 'awards';
            $model = 'awards';
        }elseif($for == 6){
            $this->objectid = $id;
            $this->uploadfor = 'category';
            $model = 'category';
        }
        $key = JSLEARNMANAGERincluder::getJSModel($model)->getMessagekey();
        // Do our thing. WordPress will move the file to 'uploads/mycustomdir'.
        $result = array();
        $filename = '';
        $return = true;
        // Register our path override.
        add_filter( 'upload_dir',  array($this,'jslearnmanager_upload_dir'));
        $uploadfilesize = $file['size'] / 1024; //kb
        $filedata = array();
        if($uploadfilesize > $file_size){
            JSLEARNMANAGERmessages::setLayoutMessage( __('Error file size too large', 'learn-manager'), 'error', $key);;
            $return = 5;
        }else{
            $filetyperesult = wp_check_filetype($file['name']);
            if(!empty($filetyperesult['ext']) && !empty($filetyperesult['type'])){
                $image_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
                $file_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('file_file_type');
                if(strstr($image_file_types, $filetyperesult['ext']) || strstr($file_file_types, $filetyperesult['ext'])){
                    $override = array('test_form' => false);
                    $result = wp_handle_upload($file,$override);
                    if ($result && ! isset($result['error'])) {
                        $filedata[0] = basename( $result['file'] );
                        $filedata[1] = $filetyperesult['ext'];
                        $filedata[2] = $result['url'];
                        $filedata[3] = $file['size'];
                        $filedata[4] = $result['file'];
                    }else{
                        JSLEARNMANAGERMessages::setLayoutMessage($result['error'], 'error',$key);
                    }
                }else{
                    $return = 5;
                }
            }else{
                $return = 6;
            }
        }
        // Set everything back to normal.
        $wpdir = wp_upload_dir();
        $this->jslearnmanager_upload_dir($wpdir , 1);
        remove_filter( 'upload_dir', array($this,'jslearnmanager_upload_dir'));
        $db = new jslearnmanagerdb();
        if($for == 0){
            if($return === true){
                $query = "UPDATE `#__js_learnmanager_course` SET logofilename = '".$filedata[0]."', logoisfile = 1 , file ='".$filedata[2]."' WHERE id =" .$id;
                $db->setQuery($query);
                $db->query();
                return $filedata;
            }else{
                $query = "UPDATE `#__js_learnmanager_course` SET logofilename = '', logoisfile = -1 , file='' WHERE id =".$id;
                $db->setQuery($query);
                $db->query();
                return $filedata;
            }
        }elseif($for == 2){
            if($return === true){
                return $filedata;
            }
        }elseif($for == 3){
            return $filedata;
        }elseif($for == 4){
            if($return == true){
                $query = "UPDATE `#__js_learnmanager_payouts` SET file ='".$filedata[2]."' WHERE id =" .$id;
                $db->setQuery($query);
                $db->query();
                return $filedata;
            }
        }elseif ($for == 5) {
            $query = "UPDATE `#__js_learnmanager_awards_list` SET image_url ='".$filedata[2]."' WHERE id =" .$id;
            $db->setQuery($query);
            $db->query();
            return $filedata;
        }elseif ($for == 6) {
            $query = "UPDATE `#__js_learnmanager_category` SET category_img ='".$filedata[2]."' WHERE id =" .$id;
            $db->setQuery($query);
            $db->query();
            return $filedata;
        }

        return $return;
    }

    function storeCustomUploadFile($id, $parentid=0, $field,$for = 0){
        $file_size = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('allowed_file_size');
        if (!function_exists('wp_handle_upload')) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }
        if($for ==0 ){
            $this->objectid = $id;
            $this->uploadfor = 'course';
            $model = 'course';
        }elseif($for == 3){
            $this->objectid = $id;
            $this->uploadfor = 'profile';
            $model = 'user';
        }elseif($for == 2){
            $this->parentid = $parentid;
            $this->objectid = $id;
            $this->uploadfor = 'lecture';
            $model = 'lecture';
        }
        $key = JSLEARNMANAGERincluder::getJSModel($model)->getMessagekey();
        // Register our path override.
        add_filter( 'upload_dir', array($this,'jslearnmanager_upload_dir'));
        // Do our thing. WordPress will move the file to 'uploads/mycustomdir'.
        $result = array();
        $filename = '';
        $return = true;
        $file = array(
                'name'     => sanitize_file_name($_FILES[$field]['name']),
                'type'     => filter_var($_FILES[$field]['type'], FILTER_SANITIZE_STRING),
                'tmp_name' => filter_var($_FILES[$field]['tmp_name'], FILTER_SANITIZE_STRING),
                'error'    => filter_var($_FILES[$field]['error'], FILTER_SANITIZE_STRING),
                'size'     => filter_var($_FILES[$field]['size'], FILTER_SANITIZE_STRING)
                );

        $uploadfilesize = filter_var($_FILES[$field]['size'], FILTER_SANITIZE_STRING) / 1024; //kb
        if($uploadfilesize > $file_size){
            JSLEARNMANAGERmessages::setLayoutMessage( __('Error file size too large', 'learn-manager'), 'error', $key);;
            $return = 5;
        }else{
            $filetyperesult = wp_check_filetype(sanitize_file_name($_FILES[$field]['name']));
            if(!empty($filetyperesult['ext']) && !empty($filetyperesult['type'])){
                $image_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
                $file_file_types = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('file_file_type');
                if(strstr($image_file_types, $filetyperesult['ext']) || strstr($file_file_types, $filetyperesult['ext'])){
                    $result = wp_handle_upload($file, array('test_form' => false));
                    if ( $result && ! isset( $result['error'] ) ) {
                        $filename = basename( $result['file'] );
                    } else {
                        JSLEARNMANAGERMessages::setLayoutMessage($result['error'], 'error',$key);
                    }
                }else{
                    $return = 5;
                }
            }else{
                $return = 6;
            }
        }

        // Set everything back to normal.
        remove_filter( 'upload_dir', array($this,'jslearnmanager_upload_dir'));
        $wpdir = wp_upload_dir();
        $this->jslearnmanager_upload_dir($wpdir);
        if($for == 0){
            JSLEARNMANAGERincluder::getJSModel('course')->storeUploadFieldValueInCourseParams($id,$filename,$field);
        }elseif($for == 2){
            JSLEARNMANAGERincluder::getJSModel('lecture')->storeUploadFieldValueInLectureParams($id,$filename,$field);
        }elseif($for == 3){
            JSLEARNMANAGERincluder::getJSModel('user')->storeUploadFieldValueInParams($id,$filename,$field);
        }
        return ;
    }
}

?>
