<?php

class JSLEARNMANAGERpremiumpluginController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = "premiumplugin";
        if ($this->canAddLayout()) {
            $layout = JSLEARNMANAGERrequest::getLayout('jslmslay', null, 'step1');
            switch ($layout){
                case 'admin_step1':
                    jslearnmanager::$_data['versioncode'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                    jslearnmanager::$_data['productcode'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('productcode');
                    jslearnmanager::$_data['producttype'] = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
                break;
            }
            $module =  'premiumplugin';
            JSLEARNMANAGERincluder::include_file($layout, $module);
        }
    }

    function canAddLayout() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jslearnmanager')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jslmstask')
            return false;
        else
            return true;
    }

    function verifytransactionkey(){

        $post_data['transactionkey'] = JSLEARNMANAGERrequest::getVar('transactionkey','','');
        if($post_data['transactionkey'] != ''){


            $post_data['domain'] = site_url();
            $post_data['step'] = 'one';
            $post_data['myown'] = 1;

            $url = 'https://wplearnmanager.com/setup/index.php';

            $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>100,'sslverify'=>false));
            if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                $result = $response['body'];
                $result = json_decode($result,true);
            }else{
                $result = false;
                if(!is_wp_error($response)){
                   $error = $response['response']['message'];
               }else{
                    $error = $response->get_error_message();
               }
            }

            if(is_array($result) && isset($result['status']) && $result['status'] == 1 ){ // means everthing ok
                update_option('jslm_addon_install_data', $result);
                update_option('jslm_addon_install_data_actual_transaction_key', $post_data['transactionkey']);
                $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step2");
                wp_redirect($url);
                return;
            }else{
                if(isset($result[0]) && $result[0] == 0){
                    $error = $result[1];
                }elseif(isset($result['error']) && $result['error'] != ''){
                    $error = $result['error'];
                }
            }
        }else{
            $error = __('Please insert activation key to proceed','learn-manager').'!';
        }
        update_option( 'jslm_addon_return_data' , array());
        update_option( 'jslm_addon_return_data_status' , 0);
        update_option( 'jslm_addon_return_data_message' , $error);
        update_option( 'jslm_addon_return_data_transactionkey' , $post_data['transactionkey']);
        $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step1");
        wp_redirect($url);
        return;
    }

    function downloadandinstalladdons(){
        $post_data = JSLEARNMANAGERrequest::get('post');

        $addons_array = $post_data;
        if(isset($addons_array['token'])){
            unset($addons_array['token']);
        }
        $addon_json_array = array();

        foreach ($addons_array as $key => $value) {
            $addon_json_array[] = str_replace('learn-manager-', '', $key);
        }

        $token = $post_data['token'];
        if($token == ''){
            update_option( 'jslm_addon_return_data' , array() );
            update_option( 'jslm_addon_return_data_status' , 0);
            update_option( 'jslm_addon_return_data_message' , __('Addon Installation Failed','learn-manager').'!' );
            update_option( 'jslm_addon_return_data_transactionkey' , '');
            $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step1");
            wp_redirect($url);
            exit;
        }
        $url = 'https://wplearnmanager.com/setup/index.php?token='.$token.'&productcode='. json_encode($addon_json_array).'&domain='. site_url();

        $install_count = 0;

        $installed = $this->install_plugin($url);
        if ( !is_wp_error( $installed ) && $installed ) {
            // had to run two seprate loops to save token for all the addons even if some error is triggered by activation.
            foreach ($post_data as $key => $value) {
                if(strstr($key, 'learn-manager-')){
                    update_option('transaction_key_for_'.$key,$token);
                }
            }

            foreach ($post_data as $key => $value) {
                if(strstr($key, 'learn-manager-')){
                    $activate = activate_plugin( $key.'/'.$key.'.php' );
                    $install_count++;
                }
            }

        }else{
            update_option( 'jslm_addon_return_data' , array());
            update_option( 'jslm_addon_return_data_status' , 0) ;
            update_option( 'jslm_addon_return_data_message' , __('Addon Installation Failed','learn-manager').'!' );
            update_option( 'jslm_addon_return_data_transactionkey' , '');
            $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step1");
            wp_redirect($url);
            exit;
        }
        $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step3");
        wp_redirect($url);
    }

    function install_plugin( $plugin_zip ) {

        include(ABSPATH . "wp-admin/includes/admin.php");
        WP_Filesystem();

        $tmpfile = download_url( $plugin_zip);

        if ( !is_wp_error( $tmpfile ) && $tmpfile ) {
            $plugin_path = WP_CONTENT_DIR;
            $plugin_path = $plugin_path.'/plugins/';
            $path = jslearnmanager::$_path.'addon.zip';

            copy( $tmpfile, $path );

            $unzipfile = unzip_file( $path, $plugin_path);

            @unlink( $path ); // must unlink afterwards
            @unlink( $tmpfile ); // must unlink afterwards

            if ( is_wp_error( $unzipfile ) ) {
                update_option( 'jslm_addon_return_data' ,array());
                update_option( 'jslm_addon_return_data_status' , 0);
                update_option( 'jslm_addon_return_data_message',  __('Addon installation failed, Directory permission error','learn-manager').'!');
                update_option( 'jslm_addon_return_data_transactionkey' , '');
                $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step1");
                wp_redirect($url);
                exit;
            } else {
                return true;
            }
        }else{
            update_option('jslm_addon_return_data', array());
            update_option('jslm_addon_return_data_status' , 0);
            update_option('jslm_addon_return_data_message' , __('Addon Installation Failed, File download error','learn-manager').'!');
            update_option('jslm_addon_return_data_transactionkey', '');
            $url = admin_url("admin.php?page=jslm_premiumplugin&jslmslay=step1");
            wp_redirect($url);
            exit;
        }
    }
}
$JSLEARNMANAGERpremiumpluginController = new JSLEARNMANAGERpremiumpluginController();
?>
