<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERpagination {

    static $_limit;
    static $_offset;
    static $_currentpage;

    static function setLimit($limit){
        if(is_numeric($limit))
            self::$_limit = $limit;
    }

    static function getLimit(){
        return (int) self::$_limit;
    }

    static function getPagination($total,$layout=null) {
        if(!is_numeric($total)) return false;
        $pagenum = isset($_GET['pagenum']) ? absint(sanitize_key($_GET['pagenum'])) : 1;
        if(!self::getLimit()){
            self::setLimit(jslearnmanager::$_config['pagination_default_page_size']); // number of rows in page
        }
        self::$_offset = ( $pagenum - 1 ) * self::$_limit;
        self::$_currentpage = $pagenum;
        $num_of_pages = ceil($total / self::$_limit);
        $result = '';
        $layargs = add_query_arg('pagenum', '%#%');
        if($layout != null && get_option( 'permalink_structure' ) != ""){
            $layargs = add_query_arg(array('pagenum'=>'%#%' , 'jslmslt'=>$layout));
        }
        if(is_admin()){
        $result = paginate_links(array(
                'base' => add_query_arg('pagenum', '%#%'),
                'format' => '',
                'prev_next' => true,
                'prev_text' => __('Previous', 'learn-manager'),
                'next_text' => __('Next', 'learn-manager'),
                'total' => $num_of_pages,
                'current' => $pagenum,
                'add_args' => false,
            ));
        }else{
            $links = paginate_links( array(
                'type' => 'array',
                'base' => $layargs,
                'format' => '',
                'prev_next' => true,
                'prev_text' => '&laquo;',
                'total' => $num_of_pages,
                'current' => $pagenum,
                'next_text' => '&raquo;',
                'add_args' => false,
            ) );
            if(!empty($links) && is_array($links)){
                $result = '<ul class="pagination pagination-lg">';
                foreach($links AS $link){
                    if(strstr($link, 'current')){
                        $result .= '<li class="active">'.$link.'</li>';
                    }else{
                        $result .= '<li>'.$link.'</li>';
                    }
                }
                $result .= '</ul>';
            }
        }
        return $result;
         // $pagenum = isset($_GET['pagenum']) ? absint(sanitize_key($_GET['pagenum'])) : 1;
        // self::$_limit = jslearnmanager::$_config['pagination_default_page_size']; // number of rows in page
    }

    static function isLastOrdering($total, $pagenum) {
        $maxrecord = $pagenum * jslearnmanager::$_config['pagination_default_page_size'];
        if ($maxrecord >= $total)
            return false;
        else
            return true;
    }

}

?>
