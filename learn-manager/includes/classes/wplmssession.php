<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERwplmssession {

    public $sessionid;
    public $sessionexpire;
    private $sessiondata;
    private $datafor;

    function __construct( ) {
        // add_action( 'init', array($this , 'init') );
        $this->init();
    }

    function getSessionId(){
        return $this->sessionid;
    }

    function init(){
        if (isset($_COOKIE['_wpjslm_session_'])) {
            $cookie = stripslashes(esc_url_raw($_COOKIE['_wpjslm_session_']));
            $user_cookie = explode('/', $cookie);
            $this->sessionid = preg_replace("/[^A-Za-z0-9_]/", '', $user_cookie[0]);
            $this->sessionexpire = absint($user_cookie[1]);
            $this->nextsessionexpire = absint($user_cookie[2]);
            // Update options session expiration
            if (time() > $this->nextsessionexpire) {
                $this->jslm_set_cookies_expiration();
            }
        } else {
            $sessionid = $this->jslm_generate_id();
            $this->sessionid = $sessionid . get_option( '_wpjslm_session_', 0 );
            $this->jslm_set_cookies_expiration();
        }
        $this->jslm_set_user_cookies();
        return $this->sessionid;
    }

    private function jslm_set_cookies_expiration(){
        $this->sessionexpire = time() + (int)(30*60);
        $this->nextsessionexpire = time() + (int)(60*60);
    }

    private function jslm_generate_id(){
        require_once( ABSPATH . 'wp-includes/class-phpass.php' );
        $hash = new PasswordHash( 16, false );

        return md5( $hash->get_random_bytes( 32 ) );
    }

    private function jslm_set_user_cookies(){
        setcookie( '_wpjslm_session_', $this->sessionid . '/' . $this->sessionexpire . '/' . $this->nextsessionexpire , $this->sessionexpire, COOKIEPATH, COOKIE_DOMAIN);
        $count = get_option( '_wpjslm_session_', 0 );
        update_option( '_wpjslm_session_', ++$count);
    }

}

?>
