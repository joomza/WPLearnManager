<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcourseTable extends JSLEARNMANAGERtable {

     public $id = '';
     public $title = '';
     public $alias = '';
     public $course_code = '';
     public $subtitle = '';
     public $description = '';
     public $learningoutcomes = '';
     public $meta_description = '';
     public $logo = '';
     public $logofilename = '';
     public $logoisfile = '';
     public $file = '';
     public $video = '';
     public $price = '';
     public $currency = '';
     public $discount_price = '';
     public $access_type = '';
     public $category_id = '';
     public $course_level = '';
     public $language = '';
     public $isdiscount = '';
     public $discounttype = '';
     public $start_date = '';
     public $instructor_id = '';
     public $course_status  = '';
     public $expire_date = '';
     public $featured = '';
     public $startfeatureddate = '';
     public $endfeatureddate = '';
     public $featuredbyuid = '';
     public $course_duration = '';
     public $keywords = '';
     public $isapprove = '';
     public $isdeleted = '';
     public $params = '';
     public $paymentplan_id = '';
     public $created_at = '';
     public $updated_at = '';

    function __construct() {
        parent::__construct('course', 'id'); // tablename, primarykey
    }

}

?>
